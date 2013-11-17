<?php
require_once("../data/dbconfig.DAO.php");
require_once("../class/events.class.php");
require_once("../class/drop.class.php");
require_once("../class/participant.class.php");

class EVENTDAO
{
    public function getAllEvents()
    {
        $result = array();
        $sqldrops = "SELECT
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
					FROM events, drops, items, (
							SELECT drops.runID, SUM(drops.value) AS totalValue
							FROM  drops 
							GROUP BY drops.runID 
						) as table2
					WHERE 
					events.id = drops.runID AND 
					drops.itemID = items.id AND
					table2.runID = events.id
					ORDER BY events.time DESC, items.name ASC;";

        $sqlparticipants = "SELECT
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
							FROM events, participants, users, (
									SELECT participants.runID, count(participants.userID) AS totalParticipants
									FROM participants
									GROUP BY participants.runID
								) AS table2
							WHERE
							events.id = participants.runID AND 
							participants.userID = users.id AND
							table2.runID = events.id
							ORDER BY events.time DESC, users.name ASC;";

        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSet1 = $dbh->query($sqldrops);
        $resultSet2 = $dbh->query($sqlparticipants);
        foreach ($resultSet1 as $row) {
            $event = EVENT::create($row["eventID"], $row["eventName"], $row["eventTime"], $row["eventDesc"]);
            $drop = DROP::create($row["dropID"], $row["itemName"], $row["itemTalonID"], $row["dropValue"]);
            $event->setTotalValue($row["totalValue"]);
            $event->appendDrop($drop);
            $result[$row["eventID"]] = $event;
        }
        foreach ($resultSet2 as $row) {
            $event = EVENT::create($row["eventID"], $row["eventName"], $row["eventTime"], $row["eventDesc"]);
            $participant = PARTICIPANT::create($row["participantID"], $row["userID"], $row["userName"], $row["userMailname"], $row["participantsPaidOut"]);
            $event->setTotalParticipants($row["totalParticipants"]);
            $event->appendParticipant($participant);
            $result[$row["eventID"]] = $event;
        }
        return $result;
    }

    public function getAllEventsByParticipantID($participantID)
    {
        $result = array();
        $sqlparticipants = "SELECT
				events.id AS eventID,
				events.name AS eventName,
				events.time AS eventTime,
				events.description AS eventDesc,
				users.id AS userID,
				users.name AS userName,
				users.mailname AS userMailname,
				participants.id AS participantID,
				participants.paidOut AS participantsPaidOut
				FROM events, participants, users
				WHERE
				events.id = participants.runID AND 
				participants.userID = users.id AND
				users.id = :participantID
				ORDER BY events.time DESC, users.name ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlparticipants);
        $stmt->bindParam(':participantID', $participantID);
        $stmt->execute();
        $resultSet = $stmt->fetchAll();
        $sqlItemsByParticipantID = "SELECT
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
				FROM events, drops, items, (
						SELECT drops.runID, SUM(drops.value) AS totalValue
						FROM  drops 
						GROUP BY drops.runID 
					) as table2				
				WHERE 
				table2.runID = events.id AND
				events.id = drops.runID AND 
				drops.itemID = items.id AND ";
        $sqlUsersByParticipantID = "SELECT
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
				FROM events, participants, users, (
						SELECT participants.runID, count(participants.userID) AS totalParticipants
						FROM participants
						GROUP BY participants.runID
					) AS table2
				WHERE
				table2.runID = events.id AND
				events.id = participants.runID AND 
				participants.userID = users.id AND ";
        $list = array();
        foreach ($resultSet AS $row) {
            array_push($list, $row["eventID"]);
        }
        $list = implode(',', $list);
        $sqlItemsByParticipantID .= "events.id IN (" . $list . ") ORDER BY events.time DESC, items.name ASC;";
        $sqlUsersByParticipantID .= "events.id IN (" . $list . ") ORDER BY events.time DESC, users.name ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSet1 = $dbh->query($sqlItemsByParticipantID);
        $resultSet2 = $dbh->query($sqlUsersByParticipantID);
        foreach ($resultSet1 as $row) {
            $event = EVENT::create($row["eventID"], $row["eventName"], $row["eventTime"], $row["eventDesc"]);
            $drop = DROP::create($row["dropID"], $row["itemName"], $row["itemTalonID"], $row["dropValue"]);
            $event->setTotalValue($row["totalValue"]);
            $event->appendDrop($drop);
            $result[$row["eventID"]] = $event;

        }
        foreach ($resultSet2 as $row) {
            $event = EVENT::create($row["eventID"], $row["eventName"], $row["eventTime"], $row["eventDesc"]);
            $participant = PARTICIPANT::create($row["participantID"], $row["userID"], $row["userName"], $row["userMailname"], $row["participantsPaidOut"]);
            $event->setTotalParticipants($row["totalParticipants"]);
            $event->appendParticipant($participant);
            $result[$row["eventID"]] = $event;
        }
        if ($result) {
            return $result;
        }
        throw new Exception('Could not get eventlist by UserID.');
    }


}

?>