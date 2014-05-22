<?php
require_once("../data/event.DAO.php");

class eventservice
{
    public static function listAllEvents()
    {
        return EVENTDAO::getAllEvents();
    }

    public static function getEventByID($eventID){
        return EVENTDAO::getEventByID($eventID);
    }

    public static function addEvent($eventType, $startDate, $eventName, $recurringEvent = 0, $dayOfWeek = 0, $hourOfDay = 0){
        return EVENTDAO::addEvent($eventType, $startDate, $eventName, $recurringEvent, $dayOfWeek, $hourOfDay);
    }

    public static function deleteEvent($eventID){
        return EVENTDAO::deleteEvent($eventID);
    }
}

?>