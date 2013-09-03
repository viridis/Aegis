<?php
require_once("../data/dbconfig.DAO.php");
require_once("../class/links.class.php");

class USEFULLLINKSDAO{
	public function getLinks($amount){
		$result = array();
		$sql = "SELECT * FROM usefulllinks ORDER BY weight DESC LIMIT ". $amount .";";
		$dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
		$resultSet = $dbh->query($sql);
		foreach ($resultSet as $row){
			$link = LINKS::create($row["id"], $row["name"], $row["location"], $row["weight"]);
			array_push($result, $link);
		}
		return $result;
	}
}

class FEATUREDLINKSDAO{
	public function getLinks($amount){
		$result = array();
		$sql = "SELECT * FROM featuredlinks ORDER BY weight DESC LIMIT ". $amount .";";
		$dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
		$resultSet = $dbh->query($sql);
		foreach ($resultSet as $row){
			$link = LINKS::create($row["id"], $row["name"], $row["location"], $row["weight"]);
			array_push($result, $link);
		}
		return $result;
	}
}

class NAVBARLINKSDAO{
	public function getLinks(){
		$result = array();
		$sql = "SELECT * FROM navbarlinks ORDER BY id DESC;";
		$dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
		$resultSet = $dbh->query($sql);
		foreach ($resultSet as $row){
			$link = LINKS::create($row["id"], $row["name"], $row["location"], $row["weight"]);
			array_push($result, $link);
		}
		return $result;
	}
}

?>