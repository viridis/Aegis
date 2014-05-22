<?php
class EVENT
{
    private static $idList = array();
    private $eventID;
    private $eventName;
    private $eventType;
    private $startDate;
    private $completeDate;
    private $eventState;
    private $recurringEvent;
    private $dayOfWeek;
    private $hourOfDay;

    function __construct($eventID, $eventType, $startDate, $completeDate,
                         $eventState, $recurringEvent, $dayOfWeek, $hourOfDay, $eventName)
    {
        $this->eventID = $eventID;
        $this->eventName = $eventName;
        $this->eventType = $eventType;
        $this->startDate = $startDate;
        $this->completeDate = $completeDate;
        $this->eventState = $eventState;
        $this->recurringEvent = $recurringEvent;
        $this->dayOfWeek = $dayOfWeek;
        $this->hourOfDay = $hourOfDay;
    }

    public static function create($eventID, $eventType, $startDate, $completeDate,
                                  $eventState, $recurringEvent, $dayOfWeek, $hourOfDay, $eventName)
    {
        if (!isset(self::$idList[$eventID])) {
            self::$idList[$eventID] = new EVENT($eventID, $eventType, $startDate, $completeDate,
                $eventState, $recurringEvent, $dayOfWeek, $hourOfDay, $eventName);

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

    public function getEventType(){
        return $this->eventType;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function getCompleteDate(){
        return $this->completeDate;
    }

    public function getEventState(){
        return $this->eventState;
    }

    public function isRecurringEvent(){
        return $this->recurringEvent;
    }

    public function getDayOfWeek(){
        return $this->dayOfWeek;
    }

    public function getHourOfDay(){
        return $this->hourOfDay;
    }
}
?>