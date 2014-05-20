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
}

?>