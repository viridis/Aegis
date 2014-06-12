<?php

class Event
{
    private static $idList = array();

    private $eventID;
    private $eventTypeID;
    private $startDate;
    private $completeDate;
    private $eventState;
    private $recurringEvent;
    private $dayOfWeek;
    private $hourOfDay;

    //Associated fields
    private $slotList; // List of slot objects
    private $dropList; // List of drop objects
    private $eventName;
    private $accountCooldown;
    private $characterCooldown;

    public function __construct($eventID, $eventTypeID, $startDate, $completeDate,
                                $eventState, $recurringEvent, $dayOfWeek, $hourOfDay)
    {
        $this->eventID = $eventID;
        $this->eventTypeID = $eventTypeID;
        $this->startDate = $startDate;
        $this->completeDate = $completeDate;
        $this->eventState = $eventState;
        $this->recurringEvent = $recurringEvent;
        $this->dayOfWeek = $dayOfWeek;
        $this->hourOfDay = $hourOfDay;
    }

    public static function create($eventID, $eventTypeID, $startDate, $completeDate,
                                  $eventState, $recurringEvent, $dayOfWeek, $hourOfDay)
    {
        if (!isset(self::$idList[$eventID])) {
            self::$idList[$eventID] = new Event($eventID, $eventTypeID, $startDate, $completeDate,
                $eventState, $recurringEvent, $dayOfWeek, $hourOfDay);

        }
        return self::$idList[$eventID];
    }

    public function getEventID()
    {
        return $this->eventID;
    }

    public function getEventName()
    {
        return $this->eventName;
    }

    public function getEventTypeID()
    {
        return $this->eventTypeID;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function getCompleteDate()
    {
        return $this->completeDate;
    }

    public function getEventState()
    {
        return $this->eventState;
    }

    public function isRecurringEvent()
    {
        return $this->recurringEvent;
    }

    public function getDayOfWeek()
    {
        return $this->dayOfWeek;
    }

    public function getHourOfDay()
    {
        return $this->hourOfDay;
    }

    public function getSlotList()
    {
        return $this->slotList;
    }

    public function getDropList()
    {
        return $this->dropList;
    }

    public function getCharacterCooldown()
    {
        return $this->characterCooldown;
    }

    public function getAccountCooldown()
    {
        return $this->accountCooldown;
    }

    public function setSlotList($slotList)
    {
        $this->slotList = $slotList;
    }

    public function setDropList($dropList)
    {
        $this->dropList = $dropList;
    }

    public function setEventName($eventName)
    {
        $this->eventName = $eventName;
    }

    public function setEventTypeID($eventTypeID)
    {
        $this->eventTypeID = $eventTypeID;
    }

    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    public function setCompleteDate($completeDate)
    {
        $this->completeDate = $completeDate;
    }

    public function setEventState($eventState)
    {
        $this->eventState = $eventState;
    }

    public function setRecurringEvent($recurringEvent)
    {
        $this->recurringEvent = $recurringEvent;
    }

    public function setDayOfWeek($dayOfWeek)
    {
        $this->dayOfWeek = $dayOfWeek;
    }

    public function setHourOfDay($hourOfDay)
    {
        $this->hourOfDay = $hourOfDay;
    }

    public function setCharacterCooldown($characterCooldown)
    {
        $this->characterCooldown = $characterCooldown;
    }

    public function setAccountCooldown($accountCooldown)
    {
        $this->accountCooldown = $accountCooldown;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}