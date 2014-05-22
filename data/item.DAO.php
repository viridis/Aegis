<?php
require_once("../data/dbconfig.DAO.php");
require_once("../data/log.DAO.php");
require_once("../class/item.class.php");

class ITEMDAO
{
    public static function getAllItems()
    {
        $result = array();
        $sql = "SELECT * FROM items ORDER BY name ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSet = $dbh->query($sql);
        foreach ($resultSet as $row) {
            $item = ITEM::create($row["itemID"], $row["aegisName"], $row["name"]);
            array_push($result, $item);
        }
        return $result;
    }

    public static function getItemById($itemID)
    {
        $sql = "SELECT * FROM items WHERE itemID = :itemID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':itemID', $itemID);
        $stmt->execute();
        $resultSet = $stmt->fetchAll();
        if ($resultSet) {
            foreach ($resultSet as $row) {
                $item = ITEM::create($row["itemID"], $row["aegisName"], $row["name"]);
                return $item;
            }
        } else {
            throw new Exception('No item found.');
        }
    }

    public static function addItem($itemID, $aegisName, $name)
    {
        $sql = "INSERT INTO items VALUES (:itemID, :aegisName, :name);";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':itemID', $itemID);
        $stmt->bindParam(':aegisName', $aegisName);
        $stmt->bindParam(':name', $name);
        $binds = array(
            ":itemID" => $itemID,
            ":aegisName" => $aegisName,
            ":name" => $name
        );
        $logdao = new LOGDAO();
        if ($stmt->execute()) { //1 if success, 0 if fail
            $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            $dao = new ITEMDAO();
            return true;
        }
        $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
        throw new Exception('Failed to add item. (' . $name . ')');
        return false;
    }
}

?>