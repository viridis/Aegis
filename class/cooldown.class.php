<?php

class Cooldown
{
    private static $idList = array();

    private $cooldownID;
    private $eventID;
    private $accountID;
    private $charID;
    private $endDate;
    private $eventTypeID;
    private $cooldownType;
    private $userID;

    public function __construct($cooldownID, $eventID, $accountID, $charID, $endDate, $eventTypeID, $cooldownType, $userID)
    {
        $this->cooldownID = $cooldownID;
        $this->eventID = $eventID;
        $this->accountID = $accountID;
        $this->charID = $charID;
        $this->endDate = $endDate;
        $this->eventTypeID = $eventTypeID;
        $this->cooldownType = $cooldownType;
        $this->userID = $userID;
    }

    public static function create($cooldownID, $eventID, $accountID, $charID, $endDate, $eventTypeID, $cooldownType, $userID)
    {
        if (!isset(self::$idList[$cooldownID])) {
            self::$idList[$cooldownID] = new Cooldown($cooldownID, $eventID, $accountID, $charID, $endDate, $eventTypeID, $cooldownType, $userID);
        }
        return self::$idList[$cooldownID];
    }

    public function getCooldownID()
    {
        return $this->cooldownID;
    }

    public function getEventID()
    {
        return $this->eventID;
    }

    public function getAccountID()
    {
        return $this->accountID;
    }

    public function getCharID()
    {
        return $this->charID;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    public function getCooldownType()
    {
        return $this->cooldownType;
    }

    public function getEventTypeID()
    {
        return $this->eventTypeID;
    }

    public function getUserID()
    {
        return $this->userID;
    }

    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    public function setCooldownType($cooldownType)
    {
        $this->cooldownType = $cooldownType;
    }

    public function setUserID($userID)
    {
        $this->userID = $userID;
    }
}