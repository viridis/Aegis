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
        $sqlCharacters = "SELECT * FROM characters WHERE userID = :id ORDER BY userID, accountID ASC;";
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
        $stmt->bindParam(':userID', $character->getUserID());
        $stmt->bindParam(':accountID', $character->getAccountID());
        $stmt->bindParam(':charName', $character->getCharName());
        $stmt->bindParam(':charClass', $character->getCharClass());
        $binds = array(
            ":userID" => $character->getUserID(),
            ":accountID" => $character->getAccountID(),
            ":charName" => $character->getCharName(),
            ":charClass" => $character->getCharClass()
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to add character to account(' . $character->getAccountID() . ')');
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

        $stmt->bindParam(':accountID', $character->getAccountID());
        $stmt->bindParam(':charName', $character->getCharName());
        $stmt->bindParam(':cooldown', $character->getCooldown());
        $stmt->bindParam(':charClass', $character->getCharClass());
        $stmt->bindParam(':userID', $character->getUserID());
        $stmt->bindParam(':charID', $character->getCharID());
        $binds = array(
            ":accountID" => $character->getAccountID(),
            ":charName" => $character->getCharName(),
            ":cooldown" => $character->getCooldown(),
            ":charClass" => $character->getCharClass(),
            ":userID" => $character->getUserID(),
            ":charID" => $character->getCharID()
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to add character to account(' . $character->getCharID() . ')');
        }
    }
}
