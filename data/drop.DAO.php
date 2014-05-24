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
        $stmt->bindParam(':eventID', $drop->getEventID());
        $stmt->bindParam(':itemID', $drop->getItemID());
        $binds = array(
            ":eventID" => $drop->getEventID(),
            ":itemID" => $drop->getItemID(),
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to create drop for eventID (' . $drop->getEventID() . ')');
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
        $stmt->bindParam(':eventID', $drop->getEventID());
        $stmt->bindParam(':holdingUserID', $drop->getHoldingUserID());
        $stmt->bindParam(':sold', $drop->isSold());
        $stmt->bindParam(':soldPrice', $drop->getSoldPrice());
        $stmt->bindParam(':itemID', $drop->getItemID());
        $stmt->bindParam(':dropID', $drop->getDropID());
        $binds = array(
            ":eventID" => $drop->getEventID(),
            ":holdingUserID" => $drop->getHoldingUserID(),
            ":sold" => $drop->isSold(),
            ":soldPrice" => $drop->getSoldPrice(),
            ":itemID" => $drop->getItemID(),
            ":dropID" => $drop->getDropID(),
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to update drop (' . $drop->getDropID() . ')');
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