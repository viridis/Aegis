<?php
class SLOT
{
    private static $idList = array();

    //DB Fields
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

    function __construct($eventID, $slotID, $slotClass, $taken, $takenUserID, $takenCharID){
        $this->eventID = $eventID;
        $this->slotID = $slotID;
        $this->slotClass = $slotClass;
        $this->taken = $taken;
        $this->takenUserID = $takenUserID;
        $this->takenCharID = $takenCharID;
    }

    public static function create($eventID, $slotID, $slotClass, $taken, $takenUserID, $takenCharID){
        if (!isset(self::$idList[$slotID])) {
            self::$idList[$slotID] = new SLOT($eventID, $slotID, $slotClass, $taken, $takenUserID, $takenCharID);
        }
        return self::$idList[$slotID];
    }

    function getEventID(){
        return $this->eventID;
    }

    function getSlotID(){
        return $this->slotID;
    }

    function getSlotClass(){
        return $this->slotClass;
    }

    function isTaken(){
        return $this->taken;
    }

    function getTakenUserID(){
        return $this->takenUserID;
    }

    function getTakenCharID(){
        return $this->takenCharID;
    }

    function getUserLogin(){
        return $this->userLogin;
    }

    function getCharClass(){
        return $this->charClass;
    }

    function getCharName(){
        return $this->charName;
    }

    function setUserLogin($userLogin){
        $this->userLogin = $userLogin;
    }

    function setCharClass($charClass){
        $this->charClass = $charClass;
    }

    function setCharName($charName){
        $this->charName = $charName;
    }
}

?>