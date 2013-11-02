<?php
require_once("../data/dbconfig.DAO.php");
require_once("../data/log.DAO.php");
require_once("../class/user.class.php");

class USERDAO{
	public function getAllUsers(){
		$result = array();
		$sql = "SELECT * FROM users ORDER BY name ASC;";
		$dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
		$resultSet = $dbh->query($sql);
		foreach ($resultSet as $row){
			$user = USER::create($row["id"], $row["name"], $row["mailname"], $row["password"]);
			array_push($result, $user);
		}
		return $result;
	}
	
	public function getUserById($id){
		$result = array();
		$sql = "SELECT * FROM users WHERE id = :id;";
		$dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $resultSet = $stmt->fetchAll();
		if($resultSet){
            foreach ($resultSet as $row){
                $user = USER::create($row["id"], $row["name"], $row["mailname"], $row["password"]);
                return $user;
            }
		}else{
            throw new Exception('No user found. ('. $username .')');
        }
	}
	
	public function addUser($username){
		$sql = "INSERT INTO users (`name`) VALUES (:username);";
		$dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':username', $username);
        $binds = array(
            ":username" => $username,
        );
        $logdao = new LOGDAO();
        if($stmt->execute()){  //1 if success, 0 if fail
            $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            $dao = new USERDAO();
            $user = $dao->getUserById($dbh->lastInsertId());
            return $user;
        }
        $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
        throw new Exception('Failed to add user. ('. $username .')');
        return false;
	}
}
?>