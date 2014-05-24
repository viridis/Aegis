<?php
class Slot
{
    private static $idList = array();

    private $eventID;
    private $slotID;
    private $slotClass;
    private $taken;
    private $takenUserID;
    private $takenCharID;

    //Associated Fields
    private $userLogin; // From user class
    private $charClass; // From character class
    private $charName; // From character class

    public function __construct($eventID, $slotID, $slotClass, $taken, $takenUserID, $takenCharID){
        $this->eventID = $eventID;
        $this->slotID = $slotID;
        $this->slotClass = $slotClass;
        $this->taken = $taken;
        $this->takenUserID = $takenUserID;
        $this->takenCharID = $takenCharID;
    }

    public static function create($eventID, $slotID, $slotClass, $taken, $takenUserID, $takenCharID){
        if (!isset(self::$idList[$slotID])) {
            self::$idList[$slotID] = new Slot($eventID, $slotID, $slotClass, $taken, $takenUserID, $takenCharID);
        }
        return self::$idList[$slotID];
    }

    public function getEventID(){
        return $this->eventID;
    }

    public function getSlotID(){
        return $this->slotID;
    }

    public function getSlotClass(){
        return $this->slotClass;
    }

    public function isTaken(){
        return $this->taken;
    }

    public function getTakenUserID(){
        return $this->takenUserID;
    }

    public function getTakenCharID(){
        return $this->takenCharID;
    }

    public function getUserLogin(){
        return $this->userLogin;
    }

    public function getCharClass(){
        return $this->charClass;
    }

    public function getCharName(){
        return $this->charName;
    }

    public function setUserLogin($userLogin){
        $this->userLogin = $userLogin;
    }

    public function setCharClass($charClass){
        $this->charClass = $charClass;
    }

    public function setCharName($charName){
        $this->charName = $charName;
    }

    public function setSlotClass($slotClass)
    {
        $this->slotClass = $slotClass;
    }
}