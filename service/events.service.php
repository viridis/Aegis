<?php
require_once("../data/event.DAO.php");

class eventservice
{
    public function listAllEvents()
    {
        $eventDAO = new EVENTDAO();
        return $eventDAO->getAllEvents();
    }

    public function getEventByID($eventID){
        $eventDAO = new EVENTDAO();
        return $eventDAO->getEventByID($eventID);
    }

    public function addEvent($eventType, $startDate, $eventName, $recurringEvent = 0, $dayOfWeek = 0, $hourOfDay = 0){
        $eventDAO = new EVENTDAO();
        return $eventDAO->addEvent($eventType, $startDate, $eventName, $recurringEvent, $dayOfWeek, $hourOfDay);
    }

    public function deleteEvent($eventID){
        $eventDAO = new EVENTDAO();
        return $eventDAO->deleteEvent($eventID);
    }
}

?>