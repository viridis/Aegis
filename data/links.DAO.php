<?php
require_once("../data/dbconfig.DAO.php");
require_once("../class/links.class.php");

class USEFULLINKSDAO
{
    public function getLinks($amount)
    {
        $result = array();
        $sql = "SELECT * FROM usefullinks ORDER BY weight DESC LIMIT " . $amount . ";";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSet = $dbh->query($sql);
        foreach ($resultSet as $row) {
            $link = LINKS::create($row["id"], $row["name"], $row["location"], $row["weight"]);
            array_push($result, $link);
        }
        return $result;
    }
}

class FEATUREDLINKSDAO
{
    public function getLinks($amount)
    {
        $result = array();
        $sql = "SELECT * FROM featuredlinks ORDER BY weight DESC LIMIT " . $amount . ";";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSet = $dbh->query($sql);
        foreach ($resultSet as $row) {
            $link = LINKS::create($row["id"], $row["name"], $row["location"], $row["weight"]);
            array_push($result, $link);
        }
        return $result;
    }
}

class NAVBARLINKSDAO
{
    public function getLinks($user = false)
    {
        $result = array();
        $sql = "SELECT * FROM navbarlinks ";
        if ($user) {
            $sql .= "WHERE visibility = :user ";
        }
        $sql .= "ORDER BY id DESC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        if ($user) {
            $stmt->bindParam(':user', $user);
        }
        $stmt->execute();
        $resultSet = $stmt->fetchAll();
        foreach ($resultSet as $row) {
            $link = LINKS::create($row["id"], $row["name"], $row["location"], $row["weight"]);
            array_push($result, $link);
        }
        return $result;
    }
}

?>