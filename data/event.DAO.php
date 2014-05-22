<?php
require_once("../data/dbconfig.DAO.php");
require_once("../class/events.class.php");

class EVENTDAO
{
    public static function getAllEvents()
    {
        $result = array();
        $sqlevents = "SELECT * FROM events;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSet = $dbh->query($sqlevents);
        foreach ($resultSet as $row) {
            $event = EVENT::create($row["eventID"], $row["eventType"], $row["startDate"], $row["completeDate"],
                $row["eventState"], $row["recurringEvent"], $row["dayOfWeek"], $row["hourOfDay"], $row["eventName"]);
            array_push($result, $event);
        }
        return $result;
    }

    public static function getEventByID($eventID){
        $result = array();
        $sqlevent = "SELECT * FROM events WHERE eventID = :eventID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlevent);
        $stmt->bindParam(':eventID', $eventID);
        $stmt->execute();
        $resultSet = $stmt->fetchAll();
        foreach($resultSet as $row){
            $event = EVENT::create($row["eventID"], $row["eventType"], $row["startDate"], $row["completeDate"],
               $row["eventState"], $row["recurringEvent"], $row["dayOfWeek"], $row["hourOfDay"], $row["eventName"]);
            array_push($result, $event);
        }
        return $result;
    }

    public static function addEvent($eventType, $startDate, $eventName, $recurringEvent = 0, $dayOfWeek = 0, $hourOfDay = 0){
        $sql = "INSERT INTO events VALUES(NULL, :eventType, :startDate, NULL, 0, :recurringEvent, :dayOfWeek, :hourOfDay, :eventName);";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':eventType', $eventType);
        $stmt->bindParam(':startDate', $startDate);
        $stmt->bindParam(':eventName', $eventName);
        $stmt->bindParam(':recurringEvent', $recurringEvent);
        $stmt->bindParam(':dayOfWeek', $dayOfWeek);
        $stmt->bindParam(':hourOfDay', $hourOfDay);
        $binds = array(
            ":eventType" => $eventType,
            ":startDate" => $startDate,
            ":recurringEvent" => $recurringEvent,
            ":dayOfWeek" => $dayOfWeek,
            ":hourOfDay" => $hourOfDay,
            ":eventName" => $eventName
        );
        $logdao = new LOGDAO();
        if ($stmt->execute()) {
            $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to add event (' . $userID . ')');
            return false;
        }
    }

    public static function deleteEvent($eventID){
        $sql = "DELETE FROM events WHERE eventID = $eventID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':eventID', $eventID);
        $binds = array(
            ":eventID" => $eventID
        );
        $logdao = new LOGDAO();
        if ($stmt->execute()) {
            $logdao->logPreparedStatement('DELETE', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logdao->logPreparedStatement('DELETE', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to delete event (' . $eventID . ')');
            return false;
        }
    }
}
?>