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
		$sql = "SELECT * FROM users WHERE id = ". $id .";";
		$dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
		$resultSet = $dbh->query($sql);
		if(!$resultSet){
			$error = "Query Crashed.";
			throw new Exception($error);
		}
		elseif($resultSet->rowCount() == 0){
			$error = "User Not Found.";
			throw new Exception($error);
		}
		else{
			foreach ($resultSet as $row){
				$user = USER::create($row["id"], $row["name"], $row["mailname"], $row["password"]);
				array_push($result, $user);
			}
			return $result;
		}
	}
	
	public function addUser($username){
		$result = array();
		$sql = "INSERT INTO users (`name`) VALUES ('". $username ."');";
		$dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $logdao = new LOGDAO();
        if($dbh->exec($sql)){  //1 if success, 0 if fail
            $logdao->logEntry('INSERT', $sql, 'SUCCESS');
            try{
                $dao = new USERDAO();
                $user = $dao->getUserById($dbh->lastInsertId());
                //echo "User ". $username ." succesfully added.";
                return $user;
            }
            catch(Exception $e){
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
            return true;
        }
        $logdao->logEntry('INSERT', $sql, 'FAILED');
        echo "User ". $username ." already exists.";
        return false;
	}
}
?>