<?php
require_once("../data/dbconfig.DAO.php");
require_once("../data/log.DAO.php");
require_once("../class/user.class.php");

class USERDAO{
	public function getAllUsers(){
		$result = array();
		$sql = "SELECT * FROM useraccount ORDER BY name ASC;";
		$dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
		$resultSet = $dbh->query($sql);
		foreach ($resultSet as $row){
			$user = USER::create($row['userID'], $row['userLogin'], $row['mailChar'], $row['roleLevel']);
			array_push($result, $user);
		}
		return $result;
	}
	
	public function getUserById($id){
		$result = array();
		$sql = "SELECT * FROM useraccount WHERE userID = :id;";
		$dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $resultSet = $stmt->fetchAll();
        if ($resultSet) {
            foreach ($resultSet as $row) {
                $user = USER::create($row['userID'], $row['userLogin'], $row['mailChar'], $row['roleLevel']);
                $user->setEmail($row["email"]);
                $user->setPassword($row["userPassword"]);
                $user->setForumName($row["forumAccount"]);
                $user->clearUser();
                return $user;
            }
        } else {
            throw new Exception('No user found. (' . $id . ')');
        }
    }

    public function addUser($username)
    {
        $sql = "INSERT INTO useraccount (`userLogin`) VALUES (:username);";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':username', $username);
        $binds = array(
            ":username" => $username,
        );
        $logdao = new LOGDAO();
        if ($stmt->execute()) { //1 if success, 0 if fail
            $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            $dao = new USERDAO();
            $user = $dao->getUserById($dbh->lastInsertId());
            return $user;
        }
        $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
        throw new Exception('Failed to add user. (' . $username . ')');
        return false;
    }

    public function updateUser($id, $name, $mailname)
    {
        $sql = 'UPDATE useraccount SET userLogin = :name, mailChar = :mailname WHERE userID = :id;';
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':mailname', $mailname);
        $stmt->bindParam(':id', $id);
        $binds = array(
            ":name" => $name,
            ":id" => $id,
            ":mailname" => $mailname,
        );
        $logdao = new LOGDAO();
        if ($stmt->execute()) { //1 if success, 0 if fail
            $logdao->logPreparedStatement('UPDATE', $stmt, $binds, 'SUCCESS');
            $dao = new USERDAO();
            $user = $dao->getUserById($id);
            return $user;
        }
        $logdao->logPreparedStatement('UPDATE', $stmt, $binds, 'FAILED');
        throw new Exception('Failed to update user. (' . $name . ')');
        return false;
    }

    public function getUserByNameAndPassword($name, $password)
    {
        $sql = 'SELECT * FROM useraccount WHERE userLogin = :name AND userPassword = :password LIMIT 1';
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $resultSet = $stmt->fetchAll();
        foreach ($resultSet as $row) {
            $user = USER::create($row["userID"], $row["userLogin"], $row["mailChar"], $row["roleLevel"]);
            return $user;
        }
        return false;
	}

    public function editUser($id, $mailName, $forumName, $email){
        $sql = 'UPDATE useraccount SET mailChar = :mailname, forumAccount = :forumname, email = :email WHERE userID = :id;';
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':mailname', $mailName);
        $stmt->bindParam(':forumname', $forumName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id);
        $binds = array(
            ":mailname" => $mailName,
            ":forumname" => $forumName,
            ":email" => $email,
            ":id" => $id,
        );
        $logdao = new LOGDAO();
        if ($stmt->execute()) { //1 if success, 0 if fail
            $logdao->logPreparedStatement('UPDATE', $stmt, $binds, 'SUCCESS');
            $dao = new USERDAO();
            $user = $dao->getUserById($id);
            return $user;
        }
        $logdao->logPreparedStatement('UPDATE', $stmt, $binds, 'FAILED');
        throw new Exception('Failed to update user. (' . $mailName . ')');
        return false;
    }

    public function editPasswordOfUser($id, $password){
        $sql = 'UPDATE useraccount SET userPassword = :password WHERE userID = :id;';
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':id', $id);
        $binds = array(
            ":password" => $password,
            ":id" => $id,
        );
        $logdao = new LOGDAO();
        if ($stmt->execute()) { //1 if success, 0 if fail
            $logdao->logPreparedStatement('UPDATE', $stmt, $binds, 'SUCCESS');
            $dao = new USERDAO();
            $user = $dao->getUserById($id);
            return $user;
        }
        $logdao->logPreparedStatement('UPDATE', $stmt, $binds, 'FAILED');
        throw new Exception('Failed to update password.');
        return false;
    }
}

?>