<?php
require_once("../data/gameAccount.DAO.php");

class GameAccountService
{
    public function getAllGameAccounts()
    {
        $gameAccountDAO = new GameAccountDAO();
        return $gameAccountDAO->getAllGameAccounts();
    }

    public function getGameAccountByUserID($userID)
    {
        $gameAccountDAO = new GameAccountDAO();
        return $gameAccountDAO->getGameAccountsByUserID($userID);
    }

    public function createGameAccount($gameAccount)
    {
        $gameAccountDAO = new GameAccountDAO();
        return $gameAccountDAO->createGameAccount($gameAccount);
    }

    public function deleteGameAccount($gameAccount)
    {
        /** @var $gameAccount GameAccount */
        $gameAccountDAO = new GameAccountDAO();
        return $gameAccountDAO->deleteGameAccount($gameAccount->getAccountID());
    }

    public function updateGameAccount($gameAccount)
    {
        $gameAccountDAO = new GameAccountDAO();
        $gameAccountDAO->updateGameAccount($gameAccount);
    }

    public function getGameAccountByAccountID($accountID)
    {
        $gameAccountDAO = new GameAccountDAO();
        return $gameAccountDAO->getGameAccountByAccountID($accountID);
    }

}