<?php
require_once("../data/dbconfig.DAO.php");
require_once("../data/log.DAO.php");
require_once("../class/slotClass.class.php");

class SlotClassDAO
{
    public function getAllSlotClasses()
    {
        $sqlSlotClass = "SELECT * FROM slotClasses;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSetSlotClass = $dbh->query($sqlSlotClass);
        $slotClassResults = $resultSetSlotClass->fetchAll(PDO::FETCH_ASSOC);
        return $slotClassResults;
    }

    public function getAllSlotClassRules()
    {
        $sqlSlotClassRules = "SELECT * FROM slotClassRules ORDER BY slotClassID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSetSlotClassRules = $dbh->query($sqlSlotClassRules);
        $slotClassRulesResults = $resultSetSlotClassRules->fetchAll(PDO::FETCH_ASSOC);
        return $slotClassRulesResults;
    }

    public function getSlotClassByAttributeValuesArray($attribute, $attributeValue)
    {
        $sqlSlotClass = "SELECT *
                                FROM slotClasses
                                WHERE " . $attribute . " = '" . $attributeValue[0] . "'";
        if (count($attributeValue) > 1) {
            array_shift($attributeValue);
            foreach ($attributeValue as $value) {
                $sqlSlotClass .= "OR " . $attribute . " = '" . $value . "'";
            }
        }
        $sqlSlotClass .= ";";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSetSlotClasses = $dbh->query($sqlSlotClass);
        $slotClassResults = $resultSetSlotClasses->fetchAll(PDO::FETCH_ASSOC);
        return $slotClassResults;
    }

    public function getSlotClassRulesByAttributeValuesArray($attribute, $attributeValue)
    {
        $sqlSlotClassRules = "SELECT *
                                FROM slotClassRules
                                WHERE " . $attribute . " = '" . $attributeValue[0] . "'";
        if (count($attributeValue) > 1) {
            array_shift($attributeValue);
            foreach ($attributeValue as $value) {
                $sqlSlotClassRules .= "OR " . $attribute . " = '" . $value . "'";
            }
        }
        $sqlSlotClassRules .= "ORDER BY slotClassID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSetSlotClasses = $dbh->query($sqlSlotClassRules);
        $slotClassResults = $resultSetSlotClasses->fetchAll(PDO::FETCH_ASSOC);
        return $slotClassResults;
    }

    public function updateSlotClass($slotClass)
    {
        /** @var SlotClass $slotClass */
        $sqlUpdate = "UPDATE slotClasses
                        SET slotClassName = :slotClassName
                        WHERE slotClassID = :slotClassID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlUpdate);
        $slotClassName = $slotClass->getSlotClassName();
        $stmt->bindParam(':slotClassName', $slotClassName);
        $slotClassID = $slotClass->getSlotClassID();
        $stmt->bindParam(':slotClassID', $slotClassID);
        $binds = array(
            ":slotClassName" => $slotClassName,
            ":slotClassID" => $slotClassID
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) {
            $logDAO->logPreparedStatement('UPDATE', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logDAO->logPreparedStatement('UPDATE', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to update slot class');
        }
    }

    public function createSlotClassRule($slotClassID, $charClassID)
    {
        $sqlInsert = "INSERT INTO slotClassRules VALUES (NULL, :slotClassID, :charClassID);";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlInsert);
        $stmt->bindParam(':slotClassID', $slotClassID);
        $stmt->bindParam(':charClassID', $charClassID);
        $binds = array(
            ":slotClassID" => $slotClassID,
            ":charClassID" => $charClassID
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to create slot class rule');
        }
    }

    public function deleteSlotClassRule($slotClassID, $charClassID)
    {
        $sqlDelete = "DELETE FROM slotClassRules WHERE slotClassID = :slotClassID AND charClassID = :charClassID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlDelete);
        $stmt->bindParam(':slotClassID', $slotClassID);
        $stmt->bindParam(':charClassID', $charClassID);
        $binds = array(
            ":slotClassID" => $slotClassID,
            ":charClassID" => $charClassID
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) {
            $logDAO->logPreparedStatement('DELETE', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logDAO->logPreparedStatement('DELETE', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to delete slot class rule');
        }
    }
}