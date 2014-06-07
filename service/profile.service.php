<?php
require_once("../service/data.service.php");

class ProfileService
{
    public function getJSONGameAccount()
    {
        $dataService = new DataService();
        /** @var GameAccount $gameAccount */
        $gameAccount = $dataService->getGameAccountByAccountID($_POST["gameAccountID"]);
        print(json_encode($gameAccount->jsonSerialize()));

        foreach ($gameAccount->getCharacterList() as $character) {
            /** @var Character $character */
            print("|");
            print(json_encode($character->jsonSerialize()));
        }
    }

    public function addGameAccountToUser()
    {
        $dataService = new DataService();
        $gameAccount = new GameAccount($_SESSION["userID"], NULL, $_POST["accountName"]);
        return $dataService->createGameAccount($gameAccount);
    }

    public function addCharacterToGameAccount()
    {
        $dataService = new DataService();
        $character = new Character($_POST["accountID"], NULL, $_POST["charName"], $_POST["charClassID"], $_SESSION["userID"]);
        return $dataService->createCharacter($character);
    }
}