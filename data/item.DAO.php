<?php
require_once("../data/dbconfig.DAO.php");
require_once("../data/log.DAO.php");
require_once("../class/item.class.php");

class ItemDAO
{
    public function getAllItems()
    {
        $result = array();
        $sql = "SELECT * FROM items ORDER BY name ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSet = $dbh->query($sql);
        foreach ($resultSet as $row) {
            $item = Item::create($row["itemID"], $row["aegisName"], $row["name"]);
            array_push($result, $item);
        }
        return $result;
    }

    public function getItemByID($itemID)
    {
        $sql = "SELECT * FROM items WHERE itemID = :itemID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':itemID', $itemID);
        $stmt->execute();
        $resultSet = $stmt->fetchAll();
        if ($resultSet) {
            foreach ($resultSet as $row) {
                $item = Item::create($row["itemID"], $row["aegisName"], $row["name"]);
                return $item;
            }
        } else {
            throw new Exception('No item found.');
        }
    }

    public function createItem($itemID, $aegisName, $name)
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
        $logdao = new LogDAO();
        if ($stmt->execute()) { //1 if success, 0 if fail
            $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            $dao = new ItemDAO();
            return true;
        }
        $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
        throw new Exception('Failed to add item. (' . $name . ')');
    }
}