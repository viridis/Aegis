<?php
class CHARACTER
{
    private static $idList = array();
    private $accountID;
    private $charID;
    private $charName;
    private $cooldown;
    private $charClass;
    private $userID;

    function __construct($accountID, $charID, $charName, $cooldown, $charClass, $userID){
        $this->accountID = $accountID;
        $this->charID = $charID;
        $this->charName = $charName;
        $this->cooldown = $cooldown;
        $this->charClass = $charClass;
        $this->userID = $userID;
    }

    public static function create($accountID, $charID, $charName, $cooldown, $charClass, $userID){
        if (!isset(self::$idList[$charID])) {
            self::$idList[$charID] = new CHARACTER($accountID, $charID, $charName, $cooldown, $charClass, $userID);
        }
        return self::$idList[$charID];
    }

    function getAccountID(){
        return $this->accountID;
    }

    function getCharID(){
        return $this->charID;
    }

    function getCharName(){
        return $this->charName;
    }

    function getCooldown(){
        return $this->cooldown;
    }

    function getCharClass(){
        return $this->charClass;
    }

    function setCooldown($cooldown){
        $this->$cooldown = $cooldown;
    }
}

?>