<?php
require_once("../data/dbconfig.DAO.php");
require_once("../class/events.class.php");

class EventDAO
{
    public function getAllEvents()
    {
        $sqlEvents = "SELECT events.*, eventTypes.eventName, eventTypes.accountCooldown, eventTypes.characterCooldown
                        FROM events LEFT JOIN eventTypes ON events.eventTypeID=eventTypes.eventTypeID
                        ORDER BY eventID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSetEvents = $dbh->query($sqlEvents);
        $eventResults = $resultSetEvents->fetchAll(PDO::FETCH_ASSOC);
        return $eventResults;
    }

    public function getEventByEventID($eventID)
    {
        $sqlEvents = "SELECT events.*, eventTypes.eventName, eventTypes.accountCooldown, eventTypes.characterCooldown
                        FROM events LEFT JOIN eventTypes ON events.eventTypeID=eventTypes.eventTypeID
                        WHERE eventID = :eventID
                        ORDER BY eventID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlEvents);
        $stmt->bindParam(':eventID', $eventID);
        $stmt->execute();
        $eventResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $eventResults;
    }

    public function createEvent($event)
    {
        /** @var EVENT $event */
        $sql = "INSERT INTO events VALUES(NULL, :eventTypeID, :startDate, NULL, 0, :recurringEvent, :dayOfWeek, :hourOfDay);";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $eventTypeID = $event->getEventTypeID();
        $stmt->bindParam(':eventTypeID', $eventTypeID);
        $startDate = $event->getStartDate();
        $stmt->bindParam(':startDate', $startDate);
        $isRecurringEvent = $event->isRecurringEvent();
        $stmt->bindParam(':recurringEvent', $isRecurringEvent);
        $dayOfWeek = $event->getDayOfWeek();
        $stmt->bindParam(':dayOfWeek', $dayOfWeek);
        $hourOfDay = $event->getHourOfDay();
        $stmt->bindParam(':hourOfDay', $hourOfDay);
        $binds = array(
            ":eventTypeID" => $eventTypeID,
            ":startDate" => $startDate,
            ":recurringEvent" => $isRecurringEvent,
            ":dayOfWeek" => $dayOfWeek,
            ":hourOfDay" => $hourOfDay,
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
        $sqlUpdate = "UPDATE events SET eventTypeID = :eventTypeID,
                        startDate = :startDate,
                        completeDate = :completeDate,
                        eventState = :eventState,
                        recurringEvent = :recurringEvent,
                        dayOfWeek = :dayOfWeek,
                        hourOfDay = :hourOfDay
                        WHERE eventID = :eventID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlUpdate);
        $eventTypeID = $event->getEventTypeID();
        $stmt->bindParam(':eventTypeID', $eventTypeID);
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
        $eventID = $event->getEventID();
        $stmt->bindParam(':eventID', $eventID);
        $binds = array(
            ":eventTypeID" => $eventTypeID,
            ":startDate" => $startDate,
            ":completeDate" => $completeDate,
            ":eventState" => $eventState,
            ":recurringEvent" => $isRecurringEvent,
            ":dayOfWeek" => $dayOfWeek,
            ":hourOfDay" => $hourOfDay,
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