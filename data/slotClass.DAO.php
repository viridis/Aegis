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
}