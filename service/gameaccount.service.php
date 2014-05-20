<?php
require_once("../data/gameaccount.DAO.php");

class gameaccountservice
{
    public static function getGameAccountByUser($userID){
        $gameAccounts = GAMEACCOUNTDAO::getGameAccountsByUser($userID);
        return $gameAccounts;
    }

    public static function getGameAccountCooldown($accountID){
        return GAMEACCOUNTDAO::getGameAccountCooldown($accountID);
    }

    public static function setGameAccountCooldown($accountID, $duration){
        return GAMEACCOUNTDAO::setAccountCooldown($accountID, $duration);
    }

    public static function addGameAccount($userID){
        return GAMEACCOUNTDAO::addGameAccount($userID);
    }

    public static function deleteGameAccount($accountID){
        return GAMEACCOUNTDAO::deleteGameAccount($accountID);
    }
}
?>