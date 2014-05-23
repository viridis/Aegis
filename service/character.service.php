<?php
require_once("../data/character.DAO.php");

class characterservice{
    public function getCharactersByUserID($userID){
        $characterDAO = new CHARACTERDAO();
        return $characterDAO->getCharactersByUserID($userID);
    }

    public function setCharacterCooldown($charID, $duration){
        $characterDAO = new CHARACTERDAO();
        return $characterDAO->setCharacterCooldown($charID, $duration);
    }

    public function addCharacter($userID, $accountID, $charName, $charClass){
        $characterDAO = new CHARACTERDAO();
        return $characterDAO->addCharacter($userID, $accountID, $charName, $charClass);
    }

    public function deleteCharacter($charID){
        $characterDAO = new CHARACTERDAO();
        return $characterDAO->deleteCharacter($charID);
    }
}
?>