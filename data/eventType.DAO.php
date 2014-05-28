<?php
require_once("../data/dbconfig.DAO.php");
require_once("../data/log.DAO.php");
require_once("../class/eventType.class.php");

class EventTypeDAO
{
    public function getAllEventTypes()
    {
        $sqlEventTypes = "SELECT * FROM eventTypes ORDER BY eventTypeID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSetEventTypes = $dbh->query($sqlEventTypes);
        $eventTypeResults = $resultSetEventTypes->fetchAll(PDO::FETCH_ASSOC);
        return $eventTypeResults;
    }

    public function getEventTypeByEventTypeID($eventTypeID)
    {
        $sqlEventTypes = "SELECT * FROM eventTypes WHERE eventTypeID = :eventTypeID ORDER BY eventTypeID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlEventTypes);
        $stmt->bindParam(':eventTypeID', $eventTypeID);
        $stmt->execute();
        $eventTypeResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $eventTypeResults;
    }

    public function updateEventType($eventType)
    {
        /** @var EventType $eventType */
        $sqlUpdate = "UPDATE TABLE eventTypes
                        SET eventName = :eventName,
                        characterCooldown = :characterCooldown,
                        accountCooldown = :accountCooldown,
                        numSlots = :numSlots
                        WHERE eventTypeID = :eventTypeID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlUpdate);
        $eventName = $eventType->getEventName();
        $stmt->bindParam(':eventName', $eventName);
        $characterCooldown = $eventType->getCharacterCooldown();
        $stmt->bindParam(':characterCooldown', $characterCooldown);
        $accountCooldown = $eventType->getAccountCooldown();
        $stmt->bindParam(':accountCooldown', $accountCooldown);
        $numSlots = $eventType->getNumSlots();
        $stmt->bindParam(':numSlots', $numSlots);
        $eventTypeID = $eventType->getEventTypeID();
        $stmt->bindParam(':eventTypeID', $eventTypeID);
        $binds = array(
            ":eventName" => $eventName,
            ":characterCooldown" => $characterCooldown,
            ":accountCooldown" => $accountCooldown,
            ":numSlots" => $numSlots,
            ":eventTypeID" => $eventTypeID,
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) { //1 if success, 0 if fail
            $logDAO->logPreparedStatement('UPDATE', $stmt, $binds, 'SUCCESS');
            return true;
        }
        $logDAO->logPreparedStatement('UPDATE', $stmt, $binds, 'FAILED');
        throw new Exception('Failed to update eventType. (' . $eventTypeID . ')');
    }

    public function deleteEventType($eventTypeID)
    {
        $sqlDelete = "DELETE FROM eventTypes WHERE eventTypeID = :eventTypeID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlDelete);
        $stmt->bindParam(':eventTypeID', $eventTypeID);
        $binds = array(
            ":eventTypeID" => $eventTypeID
        );
        $logdao = new LogDAO();
        if ($stmt->execute()) {
            $logdao->logPreparedStatement('DELETE', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logdao->logPreparedStatement('DELETE', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to delete eventType (' . $eventTypeID . ')');
        }
    }

    public function createEventType($eventType)
    {
        /** @var EventType $eventType */
        $sqlInsert = "INSERT INTO eventTypes VALUES (NULL, :eventName, :characterCooldown, :accountCooldown, :numSlots);";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlInsert);
        $eventName = $eventType->getEventName();
        $stmt->bindParam(':eventName', $eventName);
        $characterCooldown = $eventType->getCharacterCooldown();
        $stmt->bindParam(':characterCooldown', $characterCooldown);
        $accountCooldown = $eventType->getAccountCooldown();
        $stmt->bindParam(':accountCooldown', $accountCooldown);
        $numSlots = $eventType->getNumSlots();
        $stmt->bindParam(':numSlots', $numSlots);
        $binds = array(
            ":eventName" => $eventName,
            ":characterCooldown" => $characterCooldown,
            ":accountCooldown" => $accountCooldown,
            ":numSlots" => $numSlots,
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) { //1 if success, 0 if fail
            $logDAO->logPreparedStatement('UPDATE', $stmt, $binds, 'SUCCESS');
            return $dbh->lastInsertId();
        }
        $logDAO->logPreparedStatement('UPDATE', $stmt, $binds, 'FAILED');
        throw new Exception('Failed to create eventType. (' . $eventName . ')');
    }
}