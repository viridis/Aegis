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
			$user = USER::create($row['id'], $row['name'], $row['mailname'], $row['permissions']);
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
        if ($resultSet) {
            foreach ($resultSet as $row) {
                $user = USER::create($row["id"], $row["name"], $row["mailname"], $row["permissions"]);
                $user->setEmail($row["email"]);
                $user->setPassword($row["password"]);
                $user->setForumName($row["forumname"]);
                $user->clearUser();
                return $user;
            }
        } else {
            throw new Exception('No user found. (' . $username . ')');
        }
    }

    public function addUser($username)
    {
        $sql = "INSERT INTO users (`name`) VALUES (:username);";
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
        $sql = 'UPDATE users SET name = :name, mailname = :mailname WHERE id = :id;';
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
        throw new Exception('Failed to update item. (' . $name . ')');
        return false;
    }

    public function getUserByNameAndPassword($name, $password)
    {
        $sql = 'SELECT * FROM users WHERE name = :name AND password = :password LIMIT 1';
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $resultSet = $stmt->fetchAll();
        foreach ($resultSet as $row) {
            $user = USER::create($row["id"], $row["name"], $row["mailname"], $row["permissions"]);
            return $user;
        }
        return false;
	}

    public function editUser($id, $mailName, $forumName, $email){
        $sql = 'UPDATE users SET mailname = :mailname, forumname = :forumname, email = :email WHERE id = :id;';
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
        $sql = 'UPDATE users SET password = :password WHERE id = :id;';
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