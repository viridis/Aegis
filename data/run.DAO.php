<?php
require_once("../data/dbconfig.DAO.php");
require_once("../class/events.class.php");

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
		$sql = "INSERT INTO `aegis`.`events` (`name`, `time`) VALUES ('". $name ."', '". $time ."');";
		$dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
		$resultSet = $dbh->exec($sql); //1 if success, 0 if fail
		if(!$resultSet){
			//echo "Item ". $itemname ." already exists.";
		}
		// else{
			// try{
				// $dao = new ITEMDAO();
				// $item = $dao->getItemById($dbh->lastInsertId());
				// //echo "Item ". $itemname ." succesfully added.";
				// return $item;
			// }
			// catch(Exception $e){
				// echo 'Caught exception: ',  $e->getMessage(), "\n";
			// }
		// }
	}
	
	public function getRunById($id){
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
}

?>