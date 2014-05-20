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
}
?>