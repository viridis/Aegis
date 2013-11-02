<?php
require_once("../data/dbconfig.DAO.php");
require_once("../data/log.DAO.php");
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
        $sql = "SELECT * FROM items WHERE id = :id;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $resultSet = $stmt->fetchAll();
        if($resultSet){
            foreach ($resultSet as $row){
                $item = ITEM::create($row["id"], $row["name"], $row["talonID"]);
                return $item;
            }
        }else{
            throw new Exception('No item found. ('. $username .')');
        }
    }

    public function addItem($itemname){
        $sql = "INSERT INTO items (`name`) VALUES (:itemname);";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':itemname', $itemname);
        $binds = array(
            ":itemname" => $itemname,
        );
        $logdao = new LOGDAO();
        if($stmt->execute()){  //1 if success, 0 if fail
            $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            $dao = new ITEMDAO();
            $item = $dao->getItemById($dbh->lastInsertId());
            return $item;
        }
        $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
        throw new Exception('Failed to add item. ('. $itemname .')');
        return false;
    }
}
?>