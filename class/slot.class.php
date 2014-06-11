<?php

class Slot
{
    private static $idList = array();

    private $eventID;
    private $slotID;
    private $slotClassID;
    private $taken;
    private $takenUserID;
    private $takenCharID;
    private $takenCharClassID;

    //Associated Fields
    private $slotClassName;
    private $userLogin; // From user class
    private $charName; // From character class
    private $accountID; // From character class
    private $takenCharClassName;

    public function __construct($eventID, $slotID, $slotClassID, $taken, $takenUserID, $takenCharID, $takenCharClassID)
    {
        $this->eventID = $eventID;
        $this->slotID = $slotID;
        $this->slotClassID = $slotClassID;
        $this->taken = $taken;
        $this->takenUserID = $takenUserID;
        $this->takenCharID = $takenCharID;
        $this->takenCharClassID = $takenCharClassID;
    }

    public static function create($eventID, $slotID, $slotClassID, $taken, $takenUserID, $takenCharID, $takenCharClassID)
    {
        if (!isset(self::$idList[$slotID])) {
            self::$idList[$slotID] = new Slot($eventID, $slotID, $slotClassID, $taken, $takenUserID, $takenCharID, $takenCharClassID);
        }
        return self::$idList[$slotID];
    }

    public function getEventID()
    {
        return $this->eventID;
    }

    public function getSlotID()
    {
        return $this->slotID;
    }

    public function getSlotClassID()
    {
        return $this->slotClassID;
    }

    public function isTaken()
    {
        return $this->taken;
    }

    public function getTakenUserID()
    {
        return $this->takenUserID;
    }

    public function getTakenCharID()
    {
        return $this->takenCharID;
    }

    public function getUserLogin()
    {
        return $this->userLogin;
    }

    public function getTakenCharClassID()
    {
        return $this->takenCharClassID;
    }

    public function getCharName()
    {
        return $this->charName;
    }

    public function getAccountID()
    {
        return $this->accountID;
    }

    public function getSlotClassName()
    {
        return $this->slotClassName;
    }

    public function getTakenCharClassName()
    {
        return $this->takenCharClassName;
    }

    public function setTaken($taken)
    {
        $this->taken = $taken;
    }

    public function setTakenUserID($takenUserID)
    {
        $this->takenUserID = $takenUserID;
    }

    public function setTakenCharID($takenCharID)
    {
        $this->takenCharID = $takenCharID;
    }

    public function setUserLogin($userLogin)
    {
        $this->userLogin = $userLogin;
    }

    public function setTakenCharClassID($charClass)
    {
        $this->takenCharClassID = $charClass;
    }

    public function setCharName($charName)
    {
        $this->charName = $charName;
    }

    public function setSlotClassID($slotClassID)
    {
        $this->slotClassID = $slotClassID;
    }

    public function setAccountID($accountID)
    {
        $this->accountID = $accountID;
    }

    public function setSlotClassName($slotClassName)
    {
        $this->slotClassName = $slotClassName;
    }

    public function setTakenCharClassName($charClassName)
    {
        $this->takenCharClassName = $charClassName;
    }
}