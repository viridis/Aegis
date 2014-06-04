<?php

class GameAccount
{
    private static $idList = array();
    private $userID;
    private $accountID;
    private $cooldown;
    private $gameAccountName;

    // Associated fields
    private $characterList;

    public function __construct($userID, $accountID, $cooldown, $gameAccountName)
    {
        $this->userID = $userID;
        $this->accountID = $accountID;
        $this->cooldown = $cooldown;
        $this->gameAccountName = $gameAccountName;
    }

    public static function create($userID, $accountID, $cooldown, $gameAccountName)
    {
        if (!isset(self::$idList[$accountID])) {
            self::$idList[$accountID] = new GameAccount($userID, $accountID, $cooldown, $gameAccountName);
        }
        return self::$idList[$accountID];
    }

    public function getAccountID()
    {
        return $this->accountID;
    }

    public function getCooldown()
    {
        return $this->cooldown;
    }

    public function getUserID()
    {
        return $this->userID;
    }

    public function getCharacterList()
    {
        return $this->characterList;
    }

    public function getGameAccountName(){
        return $this->gameAccountName;
    }

    public function setCooldown($cooldown)
    {
        $this->cooldown = $cooldown;
    }

    public function setCharacterList($characterList)
    {
        $this->characterList = $characterList;
    }

    public function setGameAccountName($gameAccountName)
    {
        $this->gameAccountName = $gameAccountName;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}