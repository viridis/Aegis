<?php
require_once("../data/character.DAO.php");

class characterservice{
    public static function getCharactersByUserID($userID){
        return CHARACTERDAO::getCharactersByUserID($userID);
    }

    public static function setCharacterCooldown($userID, $duration){
        return CHARACTERDAO::setCharacterCooldown($userID, $duration);
    }

    public static function addCharacter($userID, $accountID, $charName, $charClass){
        return CHARACTERDAO::addCharacter($userID, $accountID, $charName, $charClass);
    }

    public static function deleteCharacter($charID){
        return CHARACTERDAO::deleteCharacter($charID);
    }
}
?>