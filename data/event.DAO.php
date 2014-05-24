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
        $stmt->bindParam(':eventType', $event->getEventType());
        $stmt->bindParam(':startDate', $event->getStartDate());
        $stmt->bindParam(':eventName', $event->getEventName());
        $stmt->bindParam(':recurringEvent', $event->isRecurringEvent());
        $stmt->bindParam(':dayOfWeek', $event->getDayOfWeek());
        $stmt->bindParam(':hourOfDay', $event->getHourOfDay());
        $binds = array(
            ":eventType" => $event->getEventType(),
            ":startDate" => $event->getStartDate(),
            ":recurringEvent" => $event->isRecurringEvent(),
            ":dayOfWeek" => $event->getDayOfWeek(),
            ":hourOfDay" => $event->getHourOfDay(),
            ":eventName" => $event->getEventName()
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to add event (' . $event->getEventName() . ')');
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
        $stmt->bindParam(':eventType', $event->getEventType());
        $stmt->bindParam(':startDate', $event->getStartDate());
        $stmt->bindParam(':completeDate', $event->getCompleteDate());
        $stmt->bindParam(':eventState', $event->getEventState());
        $stmt->bindParam(':recurringEvent', $event->isRecurringEvent());
        $stmt->bindParam(':dayOfWeek', $event->getDayOfWeek());
        $stmt->bindParam(':hourOfDay', $event->getHourOfDay());
        $stmt->bindParam(':eventName', $event->getEventName());
        $stmt->bindParam(':eventID', $event->getEventID());
        $binds = array(
            ":eventType" => $event->getEventType(),
            ":startDate" => $event->getStartDate(),
            ":completeDate" => $event->getCompleteDate(),
            ":eventState" => $event->getEventState(),
            ":recurringEvent" => $event->isRecurringEvent(),
            ":dayOfWeek" => $event->getDayOfWeek(),
            ":hourOfDay" => $event->getHourOfDay(),
            ":eventName" => $event->getEventName(),
            ":eventID" => $event->getEventID(),
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) { //1 if success, 0 if fail
            $logDAO->logPreparedStatement('UPDATE', $stmt, $binds, 'SUCCESS');
            return true;
        }
        $logDAO->logPreparedStatement('UPDATE', $stmt, $binds, 'FAILED');
        throw new Exception('Failed to update event. (' . $event->getEventID() . ')');
    }
}