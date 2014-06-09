<?php

class GameAccount
{
    private static $idList = array();
    private $userID;
    private $accountID;
    private $gameAccountName;

    // Associated fields
    private $cooldownContainer = array();
    private $characterList = array();

    public function __construct($userID, $accountID, $gameAccountName)
    {
        $this->userID = $userID;
        $this->accountID = $accountID;
        $this->gameAccountName = $gameAccountName;
    }

    public static function create($userID, $accountID, $gameAccountName)
    {
        if (!isset(self::$idList[$accountID])) {
            self::$idList[$accountID] = new GameAccount($userID, $accountID, $gameAccountName);
        }
        return self::$idList[$accountID];
    }

    public function getAccountID()
    {
        return $this->accountID;
    }

    public function getCooldownContainer()
    {
        return $this->cooldownContainer;
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

    public function setCooldownContainer($cooldownContainer)
    {
        $this->cooldownContainer = $cooldownContainer;
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