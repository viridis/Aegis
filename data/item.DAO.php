<?php
require_once("../data/dbconfig.DAO.php");
require_once("../class/item.class.php");

class ITEMDAO{
	public function getAllItems(){
		$result = array();
		$sql = "SELECT * FROM items ORDER BY name ASC;";
		$dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
		$resultSet = $dbh->query($sql);
		foreach ($resultSet as $row){
			$item = ITEM::create($row["id"], $row["name"], $row["talonID"]);
			array_push($result, $item);
		}
		return $result;
	}
	
	public function getItemById($id){
		$result = array();
		$sql = "SELECT * FROM items WHERE id = ". $id .";";
		$dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
		$resultSet = $dbh->query($sql);
		if(!$resultSet){
			$error = "Query Crashed.";
			throw new Exception($error);
		}
		elseif($resultSet->rowCount() == 0){
			$error = "Item Not Found.";
			throw new Exception($error);
		}
		else{
			foreach ($resultSet as $row){
				$item = ITEM::create($row["id"], $row["name"], $row["talonID"]);
				array_push($result, $item);
			}
			return $result;
		}
	}
	
	public function addItem($itemname){
		$result = array();
		$sql = "INSERT INTO `aegis`.`items` (`name`) VALUES ('". $itemname ."');";
		$dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
		$resultSet = $dbh->query($sql);
		if(!$resultSet){
			//echo "Item ". $itemname ." already exists.";
		}
		else{
			try{
				$dao = new ITEMDAO();
				$item = $dao->getItemById($dbh->lastInsertId());
				//echo "Item ". $itemname ." succesfully added.";
				return $item;
			}
			catch(Exception $e){
				echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
		}
	}
}
?>