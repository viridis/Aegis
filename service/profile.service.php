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
}