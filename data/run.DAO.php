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
		$result = array();
		$sql = "INSERT INTO events (`name`, `time`) VALUES ('". $name ."', '". $time ."');";
		$dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $logdao = new LOGDAO();
        if($dbh->exec($sql)){  //1 if success, 0 if fail
            $logdao->logEntry('INSERT', $sql, 'SUCCESS');
            return true;
        }
        $logdao->logEntry('INSERT', $sql, 'FAILED');
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
		$resultSet1 = $dbh->query($sqldrops);
		$resultSet2 = $dbh->query($sqlparticipants);
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
		$sql = "INSERT INTO participants (`runID`, `userID`) VALUES ('". $runID ."', '". $userID ."');";
		$dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $logdao = new LOGDAO();
        if($dbh->exec($sql)){  //1 if success, 0 if fail
            $logdao->logEntry('INSERT', $sql, 'SUCCESS');
            return true;
        }
        $logdao->logEntry('INSERT', $sql, 'FAILED');
        return false;
	}
	
	public function addItemToRun($runID, $itemID){
		$sql = "INSERT INTO drops (`runID`, `itemID`) VALUES ('". $runID ."', '". $itemID ."');";
		$dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $logdao = new LOGDAO();
        if($dbh->exec($sql)){  //1 if success, 0 if fail
            $lastID = $dbh->lastInsertId();
            $logdao->logEntry('INSERT', $sql, 'SUCCESS');
            return $lastID;
        }
        $logdao->logEntry('INSERT', $sql, 'FAILED');
        return false;
	}

    public function removeParticipantFromRun($runID, $userID){
        $sql = "DELETE FROM participants WHERE `participants`.`runID` = '". $runID ."' AND `participants`.`userID` = '". $userID ."';";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $logdao = new LOGDAO();
        if($dbh->exec($sql)){  //1 if success, 0 if fail
            $logdao->logEntry('DELETE', $sql, 'SUCCESS');
            return $userID;
        }
        $logdao->logEntry('DELETE', $sql, 'FAILED');
        return false;
    }

    public function removeItemFromRun($runID, $dropID){
        $sql = "DELETE FROM drops WHERE `drops`.`id` = ". $dropID .";";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $logdao = new LOGDAO();
        if($dbh->exec($sql)){  //1 if success, 0 if fail
            $logdao->logEntry('DELETE', $sql, 'SUCCESS');
            return $dropID;
        }
        $logdao->logEntry('DELETE', $sql, 'FAILED');
        return false;
    }

    public function sellDrop($amount, $itemId, $value){
        $sql = 'SELECT * FROM drops WHERE itemID = '. $itemId .' AND value = 0 ORDER BY id ASC LIMIT '. $amount .';';
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSet = $dbh->query($sql)->fetchAll();
        if(count($resultSet) < $amount){    //trying to sell more items than exist.
            throw new Exception('Trying to sell '. $amount .' item(s) while  there are only '. count($resultSet) .'.');
        }else{
            $list = array();
            foreach($resultSet AS $row){
                array_push($list, $row['id']);
            }
            $sql = 'UPDATE drops SET value= '. $value .' WHERE id IN ('. implode(',', $list) .')';
            $logdao = new LOGDAO();
            if($dbh->exec($sql)){  //1 if success, 0 if fail
                $logdao->logEntry('UPDATE', $sql, 'SUCCESS');
                return true;
            }
            $logdao->logEntry('UPDATE', $sql, 'FAILED');
            return false;
        }
    }
}

?>