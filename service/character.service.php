<?php
require_once("../data/character.DAO.php");

class CharacterService
{
    public function getAllCharacters()
    {
        $characterDAO = new CharacterDAO();
        return $characterDAO->getAllCharacters();
    }

    public function getCharactersByUserID($userID)
    {
        $characterDAO = new CharacterDAO();
        return $characterDAO->getCharactersByUserID($userID);
    }

    public function createCharacter($character)
    {
        $characterDAO = new CharacterDAO();
        return $characterDAO->createCharacter($character);
    }

    public function deleteCharacter($character)
    {
        /** @var $character Character */
        $characterDAO = new CharacterDAO();
        return $characterDAO->deleteCharacter($character->getCharID());
    }

    public function getCharactersByAccountID($accountID){
        $characterDAO = new CharacterDAO();
        return $characterDAO->getCharactersByAccountID($accountID);
    }

    public function updateCharacter($character)
    {
        $characterDAO = new CharacterDAO();
        $characterDAO->updateCharacter($character);
    }
}