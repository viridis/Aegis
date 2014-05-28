<?php

class EventType
{
    private static $idList = array();

    private $eventTypeID;
    private $eventName;
    private $characterCooldown;
    private $accountCooldown;
    private $numSlots;

    public function __construct($eventTypeID, $eventName, $characterCooldown, $accountCooldown, $numSlots)
    {
        $this->eventTypeID = $eventTypeID;
        $this->eventName = $eventName;
        $this->characterCooldown = $characterCooldown;
        $this->accountCooldown = $accountCooldown;
        $this->numSlots = $numSlots;
    }

    public static function create($eventTypeID, $eventName, $characterCooldown, $accountCooldown, $numSlots)
    {
        if (!isset(self::$idList[$eventTypeID])) {
            self::$idList[$eventTypeID] = new EventType($eventTypeID, $eventName, $characterCooldown, $accountCooldown, $numSlots);
        }
        return self::$idList[$eventTypeID];
    }

    public function getEventTypeID()
    {
        return $this->eventTypeID;
    }

    public function getEventName()
    {
        return $this->eventName;
    }

    public function getCharacterCooldown()
    {
        return $this->characterCooldown;
    }

    public function getAccountCooldown()
    {
        return $this->accountCooldown;
    }

    public function getNumSlots()
    {
        return $this->numSlots;
    }

    public function setEventType($eventTypeID)
    {
        $this->eventTypeID = $eventTypeID;
    }

    public function setEventName($eventName)
    {
        $this->eventName = $eventName;
    }

    public function setCharacterCooldown($characterCooldown)
    {
        $this->characterCooldown = $characterCooldown;
    }

    public function setAccountCooldown($accountCooldown)
    {
        $this->accountCooldown = $accountCooldown;
    }

    public function setNumSlots($numSlots)
    {
        $this->numSlots = $numSlots;
    }
}