<?php
require_once("../data/eventType.DAO.php");

class EventTypeService
{
    public function getAllEventTypes()
    {
        $eventTypeDAO = new EventTypeDAO();
        return $eventTypeDAO->getAllEventTypes();
    }

    public function getEventTypeByEventTypeID($eventTypeID)
    {
        $eventTypeDAO = new EventTypeDAO();
        return $eventTypeDAO->getEventTypeByEventTypeID($eventTypeID);
    }

    public function createEventType($eventType)
    {
        $eventTypeDAO = new EventTypeDAO();
        return $eventTypeDAO->createEventType($eventType);
    }

    public function deleteEventType($eventType)
    {
        /** @var EventType $eventType */
        $eventTypeDAO = new EventTypeDAO();
        return $eventTypeDAO->deleteEventType($eventType->getEventTypeID());
    }

    public function updateEventType($eventType)
    {
        $eventTypeDAO = new EventTypeDAO();
        return $eventTypeDAO->updateEventType($eventType);
    }
}