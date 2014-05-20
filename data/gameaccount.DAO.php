<?php
require_once("../data/dbconfig.DAO.php");
require_once("../class/gameaccount.class.php");
require_once("../data/log.DAO.php");

class GAMEACCOUNTDAO
{
    static function getGameAccountsByUser($userID){
        $result = array();
        $sqlaccounts = "SELECT * FROM gameaccounts WHERE userID = :userID ORDER BY accountID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlaccounts);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
        $resultSet = $stmt->fetchAll();
        foreach($resultSet as $row){
            $gameAccount = GAMEACCOUNT::create($row["userID"], $row["accountID"], $row["cooldown"]);
            array_push($result, $gameAccount);
        }
        return $result;
    }

    static function getGameAccountCooldown($accountID){
        $sqlcooldown = "SELECT cooldown FROM gameaccounts WHERE accountID = :accountID";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlcooldown);
        $stmt->bindParam(':accountID', $accountID);
        $stmt->execute();
        $resultSet = $stmt->fetchAll();
        foreach($resultSet as $row){
            $cooldown = $row["cooldown"];
            return $cooldown;
        }
    }

    static function setAccountCooldown($accountID, $duration){
        $sql = "UPDATE gameaccounts SET cooldown=NOW()+:duration WHERE accountID = :accountID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':duration', $duration);
        $stmt->bindParam(':accountID', $accountID);
        $binds = array(
            ":duration" => $duration,
            ":accountID" => $accountID,
        );
        $logdao = new LOGDAO();
        if ($stmt->execute()) {
            $logdao->logPreparedStatement('UPDATE', $stmt, $binds, 'SUCCESS');
            return GAMEACCOUNTDAO::getGameAccountCooldown($accountID);
        } else {
            $logdao->logPreparedStatement('UPDATE', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to update account cooldown id (' . $accountID . ')');
            return false;
        }
    }

    static function addGameAccount($userID){
        $sql = "INSERT INTO gameaccounts VALUES(NULL, :userID, NULL);";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':userID', $userID);
        $binds = array(
            ":userID" => $userID,
        );
        $logdao = new LOGDAO();
        if ($stmt->execute()) {
            $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to add game account (' . $userID . ')');
            return false;
        }
    }

    static function deleteGameAccount($accountID){
        $sql = "DELETE FROM gameaccounts WHERE accountID = :accountID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':accountID', $accountID);
        $binds = array(
            ":accountID" => $accountID,
        );
        $logdao = new LOGDAO();
        if ($stmt->execute()) {
            $logdao->logPreparedStatement('DELETE', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logdao->logPreparedStatement('DELETE', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to delete game account (' . $accountID . ')');
            return false;
        }
    }
}
?>