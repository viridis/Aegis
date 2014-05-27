<?php
require_once("../data/dbconfig.DAO.php");
require_once("../class/events.class.php");

class EventDAO
{
    public function getAllEvents()
    {
        $sqlEvents = "SELECT * FROM events ORDER BY eventID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSetEvents = $dbh->query($sqlEvents);
        $eventResults = $resultSetEvents->fetchAll();
        return $eventResults;
    }

    public function getEventByEventID($eventID)
    {
        $sqlEvents = "SELECT * FROM events WHERE eventID = :eventID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlEvents);
        $stmt->bindParam(':eventID', $eventID);
        $stmt->execute();
        $eventResults = $stmt->fetchAll();
        return $eventResults;
    }

    public function createEvent($event)
    {
        /** @var EVENT $event */
        $sql = "INSERT INTO events VALUES(NULL, :eventType, :startDate, NULL, 0, :recurringEvent, :dayOfWeek, :hourOfDay, :eventName);";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $eventType = $event->getEventType();
        $stmt->bindParam(':eventType', $eventType);
        $startDate = $event->getStartDate();
        $stmt->bindParam(':startDate', $startDate);
        $eventName = $event->getEventName();
        $stmt->bindParam(':eventName', $eventName);
        $isRecurringEvent = $event->isRecurringEvent();
        $stmt->bindParam(':recurringEvent', $isRecurringEvent);
        $dayOfWeek = $event->getDayOfWeek();
        $stmt->bindParam(':dayOfWeek', $dayOfWeek);
        $hourOfDay = $event->getHourOfDay();
        $stmt->bindParam(':hourOfDay', $hourOfDay);
        $binds = array(
            ":eventType" => $eventType,
            ":startDate" => $startDate,
            ":recurringEvent" => $isRecurringEvent,
            ":dayOfWeek" => $dayOfWeek,
            ":hourOfDay" => $hourOfDay,
            ":eventName" => $eventName
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return $dbh->lastInsertId();
        } else {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to add event (' . $eventName . ')');
        }
    }

    public function deleteEvent($eventID)
    {
        $sql = "DELETE FROM events WHERE eventID = $eventID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':eventID', $eventID);
        $binds = array(
            ":eventID" => $eventID
        );
        $logdao = new LogDAO();
        if ($stmt->execute()) {
            $logdao->logPreparedStatement('DELETE', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logdao->logPreparedStatement('DELETE', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to delete event (' . $eventID . ')');
        }
    }

    public function updateEvent($event)
    {
        /** @var EVENT $event */
        $sqlUpdate = "UPDATE events SET eventType = :eventType,
                        startDate = :startDate,
                        completeDate = :completeDate,
                        eventState = :eventState,
                        recurringEvent = :recurringEvent,
                        dayOfWeek = :dayOfWeek,
                        hourOfDay = :hourOfDay,
                        eventName = :eventName
                        WHERE eventID = :eventID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlUpdate);
        $eventType = $event->getEventType();
        $stmt->bindParam(':eventType', $eventType);
        $startDate = $event->getStartDate();
        $stmt->bindParam(':startDate', $startDate);
        $completeDate = $event->getCompleteDate();
        $stmt->bindParam(':completeDate', $completeDate);
        $eventState = $event->getEventState();
        $stmt->bindParam(':eventState', $eventState);
        $isRecurringEvent = $event->isRecurringEvent();
        $stmt->bindParam(':recurringEvent', $isRecurringEvent);
        $dayOfWeek = $event->getDayOfWeek();
        $stmt->bindParam(':dayOfWeek', $dayOfWeek);
        $hourOfDay = $event->getHourOfDay();
        $stmt->bindParam(':hourOfDay', $hourOfDay);
        $eventName = $event->getEventName();
        $stmt->bindParam(':eventName', $eventName);
        $eventID = $event->getEventID();
        $stmt->bindParam(':eventID', $eventID);
        $binds = array(
            ":eventType" => $eventType,
            ":startDate" => $startDate,
            ":completeDate" => $completeDate,
            ":eventState" => $eventState,
            ":recurringEvent" => $isRecurringEvent,
            ":dayOfWeek" => $dayOfWeek,
            ":hourOfDay" => $hourOfDay,
            ":eventName" => $eventName,
            ":eventID" => $eventID,
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) { //1 if success, 0 if fail
            $logDAO->logPreparedStatement('UPDATE', $stmt, $binds, 'SUCCESS');
            return true;
        }
        $logDAO->logPreparedStatement('UPDATE', $stmt, $binds, 'FAILED');
        throw new Exception('Failed to update event. (' . $eventID . ')');
    }
}