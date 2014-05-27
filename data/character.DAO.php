<?php
require_once("../data/dbconfig.DAO.php");
require_once("../class/character.class.php");

class CharacterDAO
{
    public function getAllCharacters()
    {
        $sqlCharacters = "SELECT * FROM characters ORDER BY userID, accountID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSetCharacter = $dbh->query($sqlCharacters);
        $characterResults = $resultSetCharacter->fetchAll(PDO::FETCH_ASSOC);
        return $characterResults;
    }

    public function getCharactersByUserID($userID)
    {
        $sqlCharacters = "SELECT * FROM characters WHERE userID = :userID ORDER BY userID, accountID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlCharacters);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
        $characterResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $characterResults;
    }

    public function getCharactersByAccountID($accountID)
    {
        $sqlCharacters = "SELECT * FROM characters WHERE accountID = :accountID ORDER BY charID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlCharacters);
        $stmt->bindParam(':accountID', $accountID);
        $stmt->execute();
        $characterResults = $stmt->fetchAll();
        return $characterResults;
    }

    public function createCharacter($character)
    {
        /** @var Character $character */
        $sqlInsert = "INSERT INTO characters VALUES(:accountID, NULL, :charName, NULL, :charClass, :userID);";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlInsert);
        $userID = $character->getUserID();
        $stmt->bindParam(':userID', $userID);
        $accountID = $character->getAccountID();
        $stmt->bindParam(':accountID', $accountID);
        $charName = $character->getCharName();
        $stmt->bindParam(':charName', $charName);
        $charClass = $character->getCharClass();
        $stmt->bindParam(':charClass', $charClass);
        $binds = array(
            ":userID" => $userID,
            ":accountID" => $accountID,
            ":charName" => $charName,
            ":charClass" => $charClass
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to add character to account(' . $accountID . ')');
        }
    }

    public function deleteCharacter($charID)
    {
        $sql = "DELETE FROM characters WHERE charID = :charID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':charID', $charID);
        $binds = array(
            ":charID" => $charID,
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) {
            $logDAO->logPreparedStatement('DELETE', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logDAO->logPreparedStatement('DELETE', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to delete character (' . $charID . ')');
        }
    }

    public function updateCharacter($character)
    {
        /** @var Character $character */
        $sqlUpdate = "UPDATE characters
                        SET accountID = :accountID,
                        charName = :charName,
                        cooldown = NOW() + :cooldown,
                        charClass = :charClass,
                        userID = :userID
                        WHERE charID = :charID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlUpdate);

        $accountID = $character->getAccountID();
        $stmt->bindParam(':accountID', $accountID);
        $charName = $character->getCharName();
        $stmt->bindParam(':charName', $charName);
        $cooldown = $character->getCooldown();
        $stmt->bindParam(':cooldown', $cooldown);
        $charClass = $character->getCharClass();
        $stmt->bindParam(':charClass', $charClass);
        $userID = $character->getUserID();
        $stmt->bindParam(':userID', $userID);
        $charID = $character->getCharID();
        $stmt->bindParam(':charID', $charID);
        $binds = array(
            ":accountID" => $accountID,
            ":charName" => $charName,
            ":cooldown" => $cooldown,
            ":charClass" => $charClass,
            ":userID" => $userID,
            ":charID" => $charID
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to add character to account(' . $charID . ')');
        }
    }
}
