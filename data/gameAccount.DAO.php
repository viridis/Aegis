<?php
require_once("../data/dbconfig.DAO.php");
require_once("../class/gameAccount.class.php");
require_once("../data/log.DAO.php");

class GameAccountDAO
{
    public function getAllGameAccounts()
    {
        $sqlGameAccounts = "SELECT * FROM gameaccounts ORDER BY userID, accountID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSetGameAccount = $dbh->query($sqlGameAccounts);
        $gameAccountResults = $resultSetGameAccount->fetchAll(PDO::FETCH_ASSOC);
        return $gameAccountResults;
    }

    public function getGameAccountsByUserID($userID)
    {
        $sqlGameAccounts = "SELECT * FROM gameaccounts WHERE userID = :userID ORDER BY userID, accountID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlGameAccounts);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
        $gameAccountResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $gameAccountResults;
    }

    public function createGameAccount($gameAccount)
    {
        /** @var GameAccount $gameAccount */
        $sqlInsert = "INSERT INTO gameaccounts VALUES(NULL, :userID, NULL);";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlInsert);
        $stmt->bindParam(':userID', $gameAccount->getUserID());
        $binds = array(
            ":userID" => $gameAccount->getUserID()
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to add game account to user(' . $gameAccount->getUserID() . ')');
        }
    }

    public function deleteGameAccount($accountID)
    {
        $sql = "DELETE FROM gameaccounts WHERE accountID = :accountID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':accountID', $accountID);
        $binds = array(
            ":accountID" => $accountID,
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) {
            $logDAO->logPreparedStatement('DELETE', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logDAO->logPreparedStatement('DELETE', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to delete game account (' . $accountID . ')');
        }
    }

    public function updateGameAccount($gameAccount)
    {
        /** @var GameAccount $gameAccount */
        $sqlUpdate = "UPDATE gameaccounts
                        SET userID = :userID,
                        cooldown = NOW()+:cooldown
                        WHERE accountID = :accountID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlUpdate);
        $stmt->bindParam(':userID', $gameAccount->getUserID());
        $stmt->bindParam(':cooldown', $gameAccount->getCooldown());
        $stmt->bindParam(':accountID', $gameAccount->getAccountID());
        $binds = array(
            ":userID" => $gameAccount->getUserID(),
            ":cooldown" => $gameAccount->getCooldown(),
            ":accountID" => $gameAccount->getAccountID(),
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) {
            $logDAO->logPreparedStatement('UPDATE', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logDAO->logPreparedStatement('UPDATE', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to update game account (' . $gameAccount->getAccountID() . ')');
        }
    }

    public function getGameAccountByAccountID($accountID)
    {
        $sqlGameAccount = "SELECT * FROM gameaccounts WHERE accountID = :accountID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlGameAccount);
        $stmt->bindParam(':accountID', $accountID);
        $stmt->execute();
        $gameAccountResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $gameAccountResults;
    }
}