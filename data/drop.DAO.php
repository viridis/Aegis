<?php
require_once("../class/drop.class.php");
require_once("../data/dbconfig.DAO.php");
require_once("../data/log.DAO.php");

class DropDAO
{
    public function getAllDrops()
    {
        $sqlDrops = "SELECT drops.*, items.name, items.aegisName
                        FROM drops
                        LEFT JOIN items ON items.itemID = drops.itemID
                        ORDER BY eventID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSetDrops = $dbh->query($sqlDrops);
        $dropResults = $resultSetDrops->fetchAll(PDO::FETCH_ASSOC);
        return $dropResults;
    }

    public function createDrop($drop)
    {
        /** @var Drop $drop */
        $sqlInsert = "INSERT INTO drops VALUES(:eventID, NULL, NULL, FALSE, NULL, :itemID);";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlInsert);
        $eventID = $drop->getEventID();
        $stmt->bindParam(':eventID', $eventID);
        $itemID = $drop->getItemID();
        $stmt->bindParam(':itemID', $itemID);
        $binds = array(
            ":eventID" => $eventID,
            ":itemID" => $itemID,
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to create drop for eventID (' . $eventID . ')');
        }
    }

    public function getDropByEventID($eventID)
    {
        $sqlDrops = "SELECT drops.*, items.name, items.aegisName
                        FROM drops
                        LEFT JOIN items ON items.itemID = drops.itemID
                        WHERE eventID = :eventID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlDrops);
        $stmt->bindParam(':eventID', $eventID);
        $stmt->execute();
        $dropResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $dropResults;
    }

    public function updateDrop($drop)
    {
        /** @var Drop $drop */
        $sqlUpdate = "UPDATE drops
                        SET eventID = :eventID,
                        holdingUserID = :holdingUserID,
                        sold = :sold,
                        soldPrice = :soldPrice,
                        itemID = :itemID
                        WHERE dropID = :dropID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlUpdate);
        $eventID = $drop->getEventID();
        $stmt->bindParam(':eventID', $eventID);
        $holdingUserID = $drop->getHoldingUserID();
        $stmt->bindParam(':holdingUserID', $holdingUserID);
        $isSold = $drop->isSold();
        $stmt->bindParam(':sold', $isSold);
        $soldPrice = $drop->getSoldPrice();
        $stmt->bindParam(':soldPrice', $soldPrice);
        $itemID = $drop->getItemID();
        $stmt->bindParam(':itemID', $itemID);
        $dropID = $drop->getDropID();
        $stmt->bindParam(':dropID', $dropID);
        $binds = array(
            ":eventID" => $eventID,
            ":holdingUserID" => $holdingUserID,
            ":sold" => $isSold,
            ":soldPrice" => $soldPrice,
            ":itemID" => $itemID,
            ":dropID" => $dropID,
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to update drop (' . $dropID . ')');
        }
    }

    public function removeDrop ($dropID)
    {
        $sqlDelete = "DELETE FROM drops WHERE dropID = :dropID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlDelete);
        $stmt->bindParam(':dropID', $dropID);
        $binds = array(
            ":dropID" => $dropID,
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) {
            $logDAO->logPreparedStatement('DELETE', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logDAO->logPreparedStatement('DELETE', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to delete drop (' . $dropID . ')');
        }
    }
}