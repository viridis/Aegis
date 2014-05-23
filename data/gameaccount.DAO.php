<?php
require_once("../data/dbconfig.DAO.php");
require_once("../data/character.DAO.php");
require_once("../class/gameaccount.class.php");
require_once("../data/log.DAO.php");

class GAMEACCOUNTDAO
{
    public function getGameAccountsByUser($userID){
        $sqlaccounts = "SELECT * FROM gameaccounts WHERE userID = :userID ORDER BY accountID ASC;";
        $sqlcharacters = "SELECT * FROM characters WHERE userID = :userID ORDER BY accountID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlaccounts);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
        $gameAccountResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = $dbh->prepare($sqlcharacters);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
        $characterResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = $this->createGameAccountArray($gameAccountResults, $characterResults);
        return $result;
    }

    public function setAccountCooldown($accountID, $duration){
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
            return true;
        } else {
            $logdao->logPreparedStatement('UPDATE', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to update account cooldown id (' . $accountID . ')');
            return false;
        }
    }

    public function addGameAccount($userID){
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

    public function deleteGameAccount($accountID){
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

    private function createGameAccountArray($gameAccountResults, $characterResults){
        $result = array();
        $characterPointer = 0;
        foreach($gameAccountResults as $row){

            $gameAccount = GAMEACCOUNT::create($row["userID"], $row["accountID"], $row["cooldown"]);
            $characterList = array();
            while($characterResults[$characterPointer]["accountID"] <= $row["accountID"] && isset($characterResults[$characterPointer])) {
                $character = CHARACTER::create($characterResults[$characterPointer]["accountID"],
                    $characterResults[$characterPointer]["charID"], $characterResults[$characterPointer]["charName"],
                    $characterResults[$characterPointer]["cooldown"], $characterResults[$characterPointer]["charClass"],
                    $characterResults[$characterPointer]["userID"]);
                array_push($characterList, $character);
                $characterPointer++;
            }
            $gameAccount->setCharacterList($characterList);
            array_push($result, $gameAccount);
        }
        return $result;
    }
}
?>