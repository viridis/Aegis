<?php

class Character
{
    private static $idList = array();

    private $accountID;
    private $charID;
    private $charName;
    private $charClassID;
    private $userID;

    private $cooldownContainer;

    public function __construct($accountID, $charID, $charName, $charClassID, $userID)
    {
        $this->accountID = $accountID;
        $this->charID = $charID;
        $this->charName = $charName;
        $this->charClassID = $charClassID;
        $this->userID = $userID;
    }

    public static function create($accountID, $charID, $charName, $charClassID, $userID)
    {
        if (!isset(self::$idList[$charID])) {
            self::$idList[$charID] = new Character($accountID, $charID, $charName, $charClassID, $userID);
        }
        return self::$idList[$charID];
    }

    public function getAccountID()
    {
        return $this->accountID;
    }

    public function getCharID()
    {
        return $this->charID;
    }

    public function getCharName()
    {
        return $this->charName;
    }

    public function getCooldownContainer()
    {
        return $this->cooldownContainer;
    }

    public function getCharClassID()
    {
        return $this->charClassID;
    }

    public function getUserID()
    {
        return $this->userID;
    }

    public function setCooldownContainer($cooldownContainer)
    {
        $this->$cooldownContainer = $cooldownContainer;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}