<?php
require_once("../class/charClass.class.php");

class charClassDAO
{
    public function getAllCharClass()
    {
        $sqlCharClass = "SELECT * FROM charClasses;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSetCharClass = $dbh->query($sqlCharClass);
        $charClassResults = $resultSetCharClass->fetchAll(PDO::FETCH_ASSOC);
        return $charClassResults;
    }
}