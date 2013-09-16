<?php
require_once("../data/dbconfig.DAO.php");
require_once("../class/events.class.php");

class PAYOUTDAO{
	public function getAllPayoutsFromEvents($eventList){
		$userList = array();
		foreach($eventList as $event){
			$eventTotalWorth = $event->getTotalValue();
			$totalParticipants = $event->getTotalParticipants()+1; //+1 = guild bank share.
			foreach($event->getParticipants() as $participant){
				$paidOut = $participant->getPaidOut();
				$toBePaid = floor($eventTotalWorth / $totalParticipants) - $paidOut;
				if(!isset($userList[$participant->getUserID()])){
					$array = array();
					$userList[$participant->getUserID()][0] = $participant;
					$userList[$participant->getUserID()][1] = $toBePaid;
				}
				else{
					$userList[$participant->getUserID()][1] += $toBePaid;
				}
				
			}
		}
		return $userList;
	}
	
	public function payOutUser($id, $eventList){
		$sql = "";
		foreach($eventList as $event){
			if($event->getTotalValue() > 0){
				$eventTotalWorth = $event->getTotalValue();
				$totalParticipants = $event->getTotalParticipants()+1; //+1 = guild bank share.
				$sql .= "UPDATE  `aegis`.`participants` SET  `paidOut` =  '". floor($eventTotalWorth / $totalParticipants) ."' WHERE  `participants`.`runID` = ". $event->getID() ." AND `participants`.`userID` = ". $id ."; ";
			}
		}
		$dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
		$result = $dbh->exec($sql);
		return $result;
	}
}

?>