<?php
class GAMEACCOUNT
{
    private static $idList = array();

    // DB fields
    private $userID;
    private $accountID;
    private $cooldown;

    // Associated fields
    private $characterList;

    function __construct($userID, $accountID, $cooldown = 0){
        $this->$userID = $userID;
        $this->$accountID = $accountID;
        $this->$cooldown = $cooldown;
    }

    public static function create($userID, $accountID, $cooldown = 0){
        if (!isset(self::$idList[$accountID])) {
            self::$idList[$accountID] = new GAMEACCOUNT($userID, $accountID, $cooldown);
        }
        return self::$idList[$accountID];
    }

    function getAccountID(){
        return $this->$accountID;
    }

    function getCooldown(){
        return $this->$cooldown;
    }

    function getUserID(){
        return $this->$userID;
    }

    function getCharacterList(){
        return $this->characterList;
    }

    function setCooldown($cooldown){
        $this->$cooldown = $cooldown;
    }

    function setCharacterList($characterList){
        $this->characterList = $characterList;
    }
}
?>