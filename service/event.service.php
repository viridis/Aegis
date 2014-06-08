<?php
require_once("../data/event.DAO.php");

class EventService
{
    public function getAllEvents()
    {
        $eventDAO = new EventDAO();
        return $eventDAO->getAllEvents();
    }

    public function getEventByEventID($eventID)
    {
        $eventDAO = new EventDAO();
        return $eventDAO->getEventByEventID($eventID);
    }

    public function createEvent($event)
    {
        $eventDAO = new EventDAO();
        return $eventDAO->createEvent($event);
    }

    public function deleteEvent($event)
    {
        /** @var $event Event */
        $eventDAO = new EventDAO();
        return $eventDAO->deleteEvent($event->getEventID());
    }

    public function updateEvent($event)
    {
        $eventDAO = new EventDAO();
        return $eventDAO->updateEvent($event);
    }

    public function getEventByAttribute($attribute, $attributeValue)
    {
        $eventDAO = new EventDAO();
        return $eventDAO->getEventByAttribute($attribute, $attributeValue);
    }
}
