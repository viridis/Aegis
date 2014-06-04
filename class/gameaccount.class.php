<?php

class GameAccount
{
    private static $idList = array();
    private $userID;
    private $accountID;
    private $cooldown;

    // Associated fields
    private $characterList;

    public function __construct($userID, $accountID, $cooldown)
    {
        $this->userID = $userID;
        $this->accountID = $accountID;
        $this->cooldown = $cooldown;
    }

    public static function create($userID, $accountID, $cooldown)
    {
        if (!isset(self::$idList[$accountID])) {
            self::$idList[$accountID] = new GameAccount($userID, $accountID, $cooldown);
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

    public function setCooldown($cooldown)
    {
        $this->cooldown = $cooldown;
    }

    public function setCharacterList($characterList)
    {
        $this->characterList = $characterList;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}