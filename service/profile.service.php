<?php
require_once("../service/data.service.php");

class ProfileService
{
    public function getJSONGameAccount(){
        $dataService = new DataService();
        /** @var GameAccount $gameAccount */
        $gameAccount = $dataService->getGameAccountByAccountID($_POST["gameAccountID"]);
        print(json_encode($gameAccount->jsonSerialize()));
        print("|");
        foreach($gameAccount->getCharacterList() as $character)
        {/** @var Character $character */
            print(json_encode($character->jsonSerialize()));
            print("|");
        }
    }

    public function addGameAccountToUser(){
        $dataService =  new DataService();
        $gameAccount = new GameAccount($_SESSION["userID"], NULL, $_POST["accountName"]);
        return $dataService->createGameAccount($gameAccount);
    }
}