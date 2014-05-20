<?php
class GAMEACCOUNT
{
    private static $idList = array();
    private $userID;
    private $accountID;
    private $cooldown;

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

    function setCooldown($cooldown){
        $this->$cooldown = $cooldown;
    }
}
?>