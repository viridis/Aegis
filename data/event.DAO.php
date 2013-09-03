<?php
require_once("../data/dbconfig.DAO.php");
require_once("../class/events.class.php");
require_once("../class/drop.class.php");
require_once("../class/user.class.php");
require_once("../class/participant.class.php");

class EVENTDAO{
	public function getAllEvents(){
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
				items.talonID AS itemTalonID
				FROM events, drops, items 
				WHERE 
				events.id = drops.runID AND 
				drops.itemID = items.id 
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
				participants.paidOut AS participantsPaidOut
				FROM events, participants, users
				WHERE
				events.id = participants.runID AND 
				participants.userID = users.id
				ORDER BY events.time DESC, users.name ASC;";

		$dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
		$resultSet1 = $dbh->query($sqldrops);
		$resultSet2 = $dbh->query($sqlparticipants);
		foreach ($resultSet1 as $row){
			$event = EVENT::create($row["eventID"], $row["eventName"], $row["eventTime"], $row["eventDesc"]);
			$drop = DROP::create($row["dropID"], $row["itemName"], $row["itemTalonID"], $row["dropValue"]);
			$event->appendDrop($drop);
			$result[$row["eventID"]] = $event;
		}
		foreach($resultSet2 as $row){
			$event = EVENT::create($row["eventID"], $row["eventName"], $row["eventTime"], $row["eventDesc"]);
			$participant = PARTICIPANT::create($row["participantID"], $row["userID"], $row["userName"], $row["userMailname"], $row["participantsPaidOut"]);
			$event->appendParticipant($participant);
			$result[$row["eventID"]] = $event;
		}
		return $result;
	}
	
	public function getAllEventsByParticipantID($participantID){
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
				users.id = ". $participantID ."
				ORDER BY events.time DESC, users.name ASC;";
		
		$dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
		$resultSet1 = $dbh->query($sqlparticipants);
		$sqlItemsByParticipantID = "SELECT  
				events.id AS eventID,
				events.name AS eventName,
				events.time AS eventTime,
				events.description AS eventDesc,
				drops.id AS dropID,
				drops.value AS dropValue,
				items.id AS itemID,
				items.name AS itemName,
				items.talonID AS itemTalonID
				FROM events, drops, items 
				WHERE 
				events.id = drops.runID AND 
				drops.itemID = items.id AND (";
		$sqlUsersByParticipantID = "SELECT
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
				participants.userID = users.id AND (";
		foreach($resultSet1 as $row){
			$sqlItemsByParticipantID .= "events.id = ". $row["eventID"] ." OR ";
			$sqlUsersByParticipantID .= "events.id = ". $row["eventID"] ." OR ";
		}
		$sqlItemsByParticipantID .= "0) ORDER BY events.time DESC, items.name ASC;"; //"OR 0" as a hackaround to get rid of the trailing OR.
		$sqlUsersByParticipantID .= "0) ORDER BY events.time DESC, users.name ASC;";
		
		$dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
		$resultSet1 = $dbh->query($sqlItemsByParticipantID);
		$resultSet2 = $dbh->query($sqlUsersByParticipantID);
		foreach ($resultSet1 as $row){
			$event = EVENT::create($row["eventID"], $row["eventName"], $row["eventTime"], $row["eventDesc"]);
			$drop = DROP::create($row["dropID"], $row["itemName"], $row["itemTalonID"], $row["dropValue"]);
			$event->appendDrop($drop);
			$result[$row["eventID"]] = $event;
		}
		foreach($resultSet2 as $row){
			$event = EVENT::create($row["eventID"], $row["eventName"], $row["eventTime"], $row["eventDesc"]);
			$participant = PARTICIPANT::create($row["participantID"], $row["userID"], $row["userName"], $row["userMailname"], $row["participantsPaidOut"]);
			$event->appendParticipant($participant);
			$result[$row["eventID"]] = $event;
		}
		return $result;	
	}

	
}

?>