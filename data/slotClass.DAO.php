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
}