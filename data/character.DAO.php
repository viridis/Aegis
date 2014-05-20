<?php
require_once("../data/dbconfig.DAO.php");
require_once("../class/character.class.php");

class CHARACTERDAO
{
    static function getCharactersByUserID($userID){
        $result = array();
        $sqlcharacters = "SELECT * FROM characters WHERE userID = :userID ORDER BY charID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlcharacters);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
        $resultSet = $stmt->fetchAll();
        foreach($resultSet as $row){
            $character = CHARACTER::create($row["accountID"], $row["charID"], $row["charName"],$row["cooldown"], $row["charClass"], $row["userID"]);
            array_push($result, $character);
        }
        return $result;
    }

    static function getCharactersByAccountID($accountID){
        $result = array();
        $sqlcharacters = "SELECT * FROM characters WHERE accountID = :accountID ORDER BY charID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlcharacters);
        $stmt->bindParam(':accountID', $accountID);
        $stmt->execute();
        $resultSet = $stmt->fetchAll();
        foreach($resultSet as $row){
            $character = CHARACTER::create($row["accountID"], $row["charID"], $row["charName"],$row["cooldown"], $row["charClass"], $row["userID"]);
            array_push($result, $character);
        }
        return $result;
    }

    static function setCharacterCooldown($charID, $duration){
        $sql = "UPDATE characters SET cooldown=NOW()+:duration WHERE charID = :charID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':duration', $duration);
        $stmt->bindParam(':charID', $charID);
        $binds = array(
            ":duration" => $duration,
            ":charID" => $charID,
        );
        $logdao = new LOGDAO();
        if ($stmt->execute()) {
            $logdao->logPreparedStatement('UPDATE', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logdao->logPreparedStatement('UPDATE', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to update character cooldown id (' . $charID . ')');
            return false;
        }
    }

    static function addCharacter($userID, $accountID, $charName, $charClass){
        $sql = "INSERT INTO characters VALUES(:accountID, NULL, :charName, NULL, :charClass, :userID);";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':userID', $userID);
        $stmt->bindParam(':accountID', $accountID);
        $stmt->bindParam(':charName', $charName);
        $stmt->bindParam(':charClass', $charClass);
        $binds = array(
            ":userID" => $userID,
            ":accountID" => $accountID,
            ":charName" => $charName,
            ":charClass" => $charClass
        );
        $logdao = new LOGDAO();
        if ($stmt->execute()) {
            $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to add character (' . $accountID . ')');
            return false;
        }
    }

    static function deleteCharacter($charID){
        $sql = "DELETE FROM characters WHERE charID = :charID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':charID', $charID);
        $binds = array(
            ":charID" => $charID,
        );
        $logdao = new LOGDAO();
        if ($stmt->execute()) {
            $logdao->logPreparedStatement('DELETE', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logdao->logPreparedStatement('DELETE', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to delete character (' . $accountID . ')');
            return false;
        }
    }
}
?>