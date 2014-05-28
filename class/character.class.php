<?php

class Character
{
    private static $idList = array();

    private $accountID;
    private $charID;
    private $charName;
    private $cooldown;
    private $charClassID;
    private $userID;

    public function __construct($accountID, $charID, $charName, $cooldown, $charClassID, $userID)
    {
        $this->accountID = $accountID;
        $this->charID = $charID;
        $this->charName = $charName;
        $this->cooldown = $cooldown;
        $this->charClassID = $charClassID;
        $this->userID = $userID;
    }

    public static function create($accountID, $charID, $charName, $cooldown, $charClassID, $userID)
    {
        if (!isset(self::$idList[$charID])) {
            self::$idList[$charID] = new Character($accountID, $charID, $charName, $cooldown, $charClassID, $userID);
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

    public function getCooldown()
    {
        return $this->cooldown;
    }

    public function getCharClassID()
    {
        return $this->charClassID;
    }

    public function getUserID()
    {
        return $this->userID;
    }

    public function setCooldown($cooldown)
    {
        $this->$cooldown = $cooldown;
    }
}