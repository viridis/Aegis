<?php
require_once("../data/dbconfig.DAO.php");
require_once("../class/links.class.php");

class UsefulLinksDAO
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

class FeaturedLinksDAO
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

class NavBarLinksDAO
{
    public function getLinksForUser($userId)
    {
        $result = array();
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        if($userId == 0){
            $sql = "SELECT *
                    FROM navbarlinks
                    WHERE visibility = 0
                    ORDER BY id ASC;
                ";
            $stmt = $dbh->prepare($sql);
        } else {
            $sql = "SELECT l.id, l.name, l.location
                    FROM navbarlinks l
                    JOIN useraccount u on u.roleLevel >= visibility
                    WHERE u.userID = :userid
                    ORDER BY l.id ASC;
                ";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':userid', $userId);
        }
        $stmt->execute();
        $resultSet = $stmt->fetchAll();
        foreach ($resultSet as $row) {
            $link = LINKS::create($row["id"], $row["name"], $row["location"]);
            array_push($result, $link);
        }
        return $result;
    }
}