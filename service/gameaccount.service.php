<?php
require_once("../data/gameaccount.DAO.php");

class gameaccountservice
{
    public function getGameAccountByUser($userID){
        $gameAccountDAO = new GAMEACCOUNTDAO();
        return $gameAccountDAO->getGameAccountsByUser($userID);
    }

    public function setGameAccountCooldown($accountID, $duration){
        $gameAccountDAO = new GAMEACCOUNTDAO();
        return $gameAccountDAO->setAccountCooldown($accountID, $duration);
    }

    public static function addGameAccount($userID){
        $gameAccountDAO = new GAMEACCOUNTDAO();
        return $gameAccountDAO->addGameAccount($userID);
    }

    public static function deleteGameAccount($accountID){
        $gameAccountDAO = new GAMEACCOUNTDAO();
       return $gameAccountDAO->deleteGameAccount($accountID);
    }
}
?>