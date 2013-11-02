<?php
require_once("../data/dbconfig.DAO.php");
require_once("../data/log.DAO.php");
require_once("../class/events.class.php");
require_once("../class/drop.class.php");
require_once("../class/participant.class.php");

class RUNDAO{
    public function getAllEvents(){
        $result = array();
        $sql = "SELECT
				events.id AS eventID,
				events.name AS eventName,
				events.time AS eventTime,
				events.description AS eventDesc
				FROM events
				ORDER BY events.time DESC;";

        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultset = $dbh->query($sql);
        foreach ($resultset as $row){
            $event = EVENT::create($row["eventID"], $row["eventName"], $row["eventTime"], $row["eventDesc"]);
            $result[$row["eventID"]] = $event;
        }
        return $result;
    }

    public function addRun($name, $time){
        $sql = "INSERT INTO events (`name`, `time`) VALUES (:name, :time);";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':time', $time);
        $binds = array(
            ":name" => $name,
            ":time" => $time,
        );
        $logdao = new LOGDAO();
        if($stmt->execute()){  //1 if success, 0 if fail
            $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return true;
        }
        $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
        return false;
    }

    public function getRunById($id){
        $result;
        $sqldrops = "select
					events.id AS eventID,
					events.name AS eventName,
					events.time AS eventTime,
					events.description AS eventDesc,
					drops.id AS dropID,
					drops.value AS dropValue,
					items.id AS itemID,
					items.name AS itemName,
					items.talonID AS itemTalonID,
					table2.totalValue as totalValue
					FROM events events
					left JOIN drops drops
						ON events.id = drops.runID
					left JOIN items items
						ON drops.itemID = items.id
					left join (SELECT drops.runID, SUM(drops.value) AS totalValue
								FROM  drops 
								GROUP BY drops.runID 
								) as table2
						ON drops.runID = table2.runID
					WHERE
					events.id = ". $id ."
					ORDER BY events.time DESC, items.name ASC;";

        $sqlparticipants = "select
							events.id AS eventID,
							events.name AS eventName,
							events.time AS eventTime,
							events.description AS eventDesc,
							users.id AS userID,
							users.name AS userName,
							users.mailname AS userMailname,
							participants.id AS participantID,
							participants.paidOut AS participantsPaidOut,
							table2.totalParticipants AS totalParticipants
							FROM events events
							left JOIN participants participants
								ON events.id = participants.runID
							left JOIN users users
								ON participants.userID = users.id
							left join (SELECT participants.runID, count(participants.userID) AS totalParticipants
										FROM participants
										GROUP BY participants.runID) table2
								ON participants.runID = table2.runID
							WHERE
							events.id = ". $id ."
							ORDER BY events.time DESC, users.name ASC;";

        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt1 = $dbh->prepare($sqldrops);
        $stmt1->bindParam(':id', $id);
        $stmt1->execute();
        $resultSet1 = $stmt1->fetchAll();
        $stmt2 = $dbh->prepare($sqlparticipants);
        $stmt2->bindParam(':id', $id);
        $stmt2->execute();
        $resultSet2 = $stmt2->fetchAll();
        foreach ($resultSet1 as $row){
            $event = EVENT::create($row["eventID"], $row["eventName"], $row["eventTime"], $row["eventDesc"]);
            if(isset($row["dropID"])){
                $drop = DROP::create($row["dropID"], $row["itemName"], $row["itemTalonID"], $row["dropValue"]);
                $event->setTotalValue($row["totalValue"]);
                $event->appendDrop($drop);
            }
            $result = $event;
        }
        foreach($resultSet2 as $row){
            $event = EVENT::create($row["eventID"], $row["eventName"], $row["eventTime"], $row["eventDesc"]);
            if(isset($row["participantID"])){
                $participant = PARTICIPANT::create($row["participantID"], $row["userID"], $row["userName"], $row["userMailname"], $row["participantsPaidOut"]);
                $event->setTotalParticipants($row["totalParticipants"]);
                $event->appendParticipant($participant);
            }
            $result = $event;
        }
        return $result;
    }

    public function addParticipantToRun($runID, $userID){
        $sql = "INSERT INTO participants (`runID`, `userID`) VALUES (:runID, :userID);";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':runID', $runID);
        $stmt->bindParam(':userID', $userID);
        $binds = array(
            ":runID" => $runID,
            ":userID" => $userID,
        );
        $logdao = new LOGDAO();
        if($stmt->execute()){  //1 if success, 0 if fail
            $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return true;
        }
        $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
        return false;
    }

    public function addItemToRun($runID, $itemID){
        $sql = "INSERT INTO drops (`runID`, `itemID`) VALUES (:runID, :itemID);";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':runID', $runID);
        $stmt->bindParam(':itemID', $itemID);
        $binds = array(
            ":runID" => $runID,
            ":itemID" => $itemID,
        );
        $logdao = new LOGDAO();
        if($stmt->execute()){  //1 if success, 0 if fail
            $lastID = $dbh->lastInsertId();
            $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return $lastID;
        }
        $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
        return false;
    }

    public function removeParticipantFromRun($runID, $userID){
        $sql = "DELETE FROM participants WHERE `participants`.`runID` = :runID AND `participants`.`userID` = :userID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':runID', $runID);
        $stmt->bindParam(':userID', $userID);
        $binds = array(
            ":runID" => $runID,
            ":userID" => $userID,
        );
        $logdao = new LOGDAO();
        if($stmt->execute()){  //1 if success, 0 if fail
            $logdao->logPreparedStatement('DELETE', $stmt, $binds, 'SUCCESS');
            return $userID;
        }
        $logdao->logPreparedStatement('DELETE', $stmt, $binds, 'FAILED');
        return false;
    }

    public function removeItemFromRun($runID, $dropID){
        $sql = "DELETE FROM drops WHERE `drops`.`id` = :dropID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':dropID', $dropID);
        $binds = array(
            ":dropID" => $dropID,
        );
        $logdao = new LOGDAO();
        if($stmt->execute()){  //1 if success, 0 if fail
            $logdao->logPreparedStatement('DELETE', $stmt, $binds, 'SUCCESS');
            return $dropID;
        }
        $logdao->logPreparedStatement('DELETE', $stmt, $binds, 'FAILED');
        return false;
    }

    public function sellDrop($amount, $itemId, $value){
        $amount = intval(trim($amount));
        $sql = 'SELECT drops.id as dropsID
                FROM drops, events
                WHERE itemID = :itemID AND value = 0 AND drops.runID = events.id ORDER BY events.time, drops.id ASC LIMIT :amount;';
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':itemID', $itemId);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
        $stmt->execute();
        $resultSet = $stmt->fetchAll();
        if(count($resultSet) < $amount){    //trying to sell more items than exist.
            throw new Exception('Trying to sell '. $amount .' item(s) while  there are only '. count($resultSet) .'.');
        }else{
            $list = array();
            foreach($resultSet AS $row){
                array_push($list, intval(trim($row['dropsID'])));
            }
            $inQuery = implode(',', array_fill(0, count($list), '?'));
            $sql = 'UPDATE drops SET value= ? WHERE id IN ('. $inQuery .');';
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(1, $value);
            foreach ($list as $k => $id){
                $stmt->bindValue(($k+2), $id);
            }
            $binds = array(
                ":value" => $value,
                ":list" => $list,
            );
            $logdao = new LOGDAO();
            if($stmt->execute()){  //1 if success, 0 if fail
                $logdao->logPreparedStatement('UPDATE', $stmt, $binds, 'SUCCESS');
                return true;
            }
            $logdao->logPreparedStatement('UPDATE', $stmt, $binds, 'FAILED');
            return false;
        }
    }
}

?>