<?php
require_once("../data/dbconfig.DAO.php");
require_once("../class/character.class.php");

class CharacterDAO
{
    public function getAllCharacters()
    {
        $sqlCharacters = "SELECT * FROM characters LEFT JOIN charClasses ON characters.charClassID=charClasses.charClassID ORDER BY userID, accountID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSetCharacter = $dbh->query($sqlCharacters);
        $characterResults = $resultSetCharacter->fetchAll(PDO::FETCH_ASSOC);
        return $characterResults;
    }

    public function getCharactersByUserID($userID)
    {
        $sqlCharacters = "SELECT * FROM characters LEFT JOIN charClasses ON characters.charClassID=charClasses.charClassID WHERE userID = :userID ORDER BY userID, accountID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlCharacters);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
        $characterResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $characterResults;
    }

    public function getCharactersByAccountID($accountID)
    {
        $sqlCharacters = "SELECT * FROM characters LEFT JOIN charClasses ON characters.charClassID=charClasses.charClassID WHERE accountID = :accountID ORDER BY userID, accountID ASC;";
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
        $sqlInsert = "INSERT INTO characters VALUES(:accountID, NULL, :charName, :charClassID, :userID);";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlInsert);
        $userID = $character->getUserID();
        $stmt->bindParam(':userID', $userID);
        $accountID = $character->getAccountID();
        $stmt->bindParam(':accountID', $accountID);
        $charName = $character->getCharName();
        $stmt->bindParam(':charName', $charName);
        $charClassID = $character->getCharClassID();
        $stmt->bindParam(':charClassID', $charClassID);
        $binds = array(
            ":userID" => $userID,
            ":accountID" => $accountID,
            ":charName" => $charName,
            ":charClassID" => $charClassID
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
                        charClass = :charClassID,
                        userID = :userID
                        WHERE charID = :charID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlUpdate);

        $accountID = $character->getAccountID();
        $stmt->bindParam(':accountID', $accountID);
        $charName = $character->getCharName();
        $stmt->bindParam(':charName', $charName);
        $charClassID = $character->getCharClassID();
        $stmt->bindParam(':charClassID', $charClassID);
        $userID = $character->getUserID();
        $stmt->bindParam(':userID', $userID);
        $charID = $character->getCharID();
        $stmt->bindParam(':charID', $charID);
        $binds = array(
            ":accountID" => $accountID,
            ":charName" => $charName,
            ":charClassID" => $charClassID,
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

    public function getCharactersByAttributeValuesArray($attribute, $attributeValue)
    {
        $sqlCharacter = "SELECT *
                        FROM characters
                        LEFT JOIN charClasses ON characters.charClassID=charClasses.charClassID
                       WHERE " . $attribute . " = '" . $attributeValue[0] . "'";
        if (count($attributeValue) > 1) {
            array_shift($attributeValue);
            foreach ($attributeValue as $value) {
                $sqlCharacter .= "OR " . $attribute . " = '" . $value . "'";
            }
        }
        $sqlCharacter .= "ORDER BY userID, accountID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSetCharacters = $dbh->query($sqlCharacter);
        $characterResults = $resultSetCharacters->fetchAll(PDO::FETCH_ASSOC);
        return $characterResults;
    }
}
