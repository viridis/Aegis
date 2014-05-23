<?php
require_once("../class/drop.class.php");
require_once("../data/dbconfig.DAO.php");
require_once("../data/log.DAO.php");

class DROPDAO
{
    public function addDrop($eventID, $itemID){
        $sql = "INSERT INTO drops VALUES(:eventID, NULL, NULL, FALSE, NULL, :itemID);";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':eventID', $eventID);
        $stmt->bindParam(':itemID', $itemID);
        $binds = array(
            ":eventID" => $eventID,
            ":itemID" => $itemID,
        );
        $logdao = new LOGDAO();
        if ($stmt->execute()) {
            $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to add drop for eventID (' . $eventID . ')');
            return false;
        }
    }

    public function getDropByEventID($eventID){
        $result = array();
        $sqldrops = "SELECT drops.*, items.name, items.aegisName
                        FROM drops
                        LEFT JOIN items ON items.itemID = drops.itemID
                        WHERE eventID = :eventID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqldrops);
        $stmt->bindParam(':eventID', $eventID);
        $stmt->execute();
        $resultSet = $stmt->fetchAll();
        foreach($resultSet as $row){
            $drop = drop::create($row["eventID"], $row["dropID"], $row["holdingUserID"], $row["sold"], $row["soldPrice"], $row["itemID"]);
            $drop->setItemName($row["name"]);
            $drop->setAegisName($row["aegisName"]);
            array_push($result, $drop);
        }
        return $result;
    }
}


?>