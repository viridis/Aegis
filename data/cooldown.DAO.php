<?php
require_once("../data/dbconfig.DAO.php");
require_once("../class/cooldown.class.php");
require_once("../data/log.DAO.php");

class CooldownDAO
{
    public function getAllCooldowns()
    {
        $sqlCooldown = "SELECT * FROM cooldowns;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSetCooldowns = $dbh->query($sqlCooldown);
        $cooldownResults = $resultSetCooldowns->fetchAll(PDO::FETCH_ASSOC);
        return $cooldownResults;
    }

    public function getCooldownByCooldownID($cooldownID)
    {
        $sqlCooldown = "SELECT * FROM cooldowns WHERE cooldownID = :cooldownID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlCooldown);
        $stmt->bindParam(':cooldownID', $cooldownID);
        $stmt->execute();
        $cooldownResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $cooldownResults;
    }

    public function createCooldown($cooldown)
    {
        /** @var Cooldown $cooldown */
        $sqlInsert = "INSERT INTO cooldowns VALUES(NULL, :eventID, :accountID, :charID, :endDate, :eventTypeID);";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlInsert);
        $eventID = $cooldown->getEventID();
        $stmt->bindParam(':eventID', $eventID);
        $accountID = $cooldown->getAccountID();
        $stmt->bindParam(':accountID', $accountID);
        $charID = $cooldown->getCharID();
        $stmt->bindParam(':charID', $charID);
        $endDate = $cooldown->getEndDate();
        $stmt->bindParam(':endDate', $endDate);
        $eventTypeID = $cooldown->getEventTypeID();
        $stmt->bindParam(':eventTypeID', $eventTypeID);
        $binds = array(
            ":eventID" => $eventID,
            ":accountID" => $accountID,
            ":charID" => $charID,
            ":endDate" => $endDate,
            ":eventTypeID" => $eventTypeID
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to create cooldown for eventID(' . $eventID . ')');
        }
    }

    public function deleteCooldown($cooldownID)
    {
        $sqlDelete = "DELETE FROM cooldowns WHERE cooldownID = :cooldownID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlDelete);
        $stmt->bindParam(':cooldownID', $cooldownID);
        $binds = array(
            ":cooldownID" => $cooldownID,
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) {
            $logDAO->logPreparedStatement('DELETE', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logDAO->logPreparedStatement('DELETE', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to delete cooldown (' . $cooldownID . ')');
        }
    }

    public function updateCooldown($cooldown)
    {
        /** @var Cooldown $cooldown */
        $sqlUpdate = "UPDATE cooldowns SET eventID = :eventID,
                        accountID = :accountID,
                        charID = :charID,
                        endDate = :endDate,
                        eventTypeID = :eventTypeID
                        WHERE cooldownID = :cooldownID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlUpdate);
        $eventID = $cooldown->getEventID();
        $stmt->bindParam(':eventID', $eventID);
        $accountID = $cooldown->getAccountID();
        $stmt->bindParam(':accountID', $accountID);
        $charID = $cooldown->getCharID();
        $stmt->bindParam(':charID', $charID);
        $endDate = $cooldown->getEndDate();
        $stmt->bindParam(':endDate', $endDate);
        $eventTypeID = $cooldown->getCooldownID();
        $stmt->bindParam(':eventTypeID', $eventTypeID);
        $cooldownID = $cooldown->getCooldownID();
        $stmt->bindParam(':cooldownID', $cooldownID);
        $binds = array(
            ":eventID" => $eventID,
            ":accountID" => $accountID,
            ":charID" => $charID,
            ":endDate" => $endDate,
            ":eventTypeID" => $eventTypeID,
            ":cooldownID" => $cooldownID
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to update cooldown (' . $cooldownID . ')');
        }
    }
}