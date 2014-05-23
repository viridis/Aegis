<?php
require_once("../data/dbconfig.DAO.php");
require_once("../data/log.DAO.php");
require_once("../class/character.class.php");
require_once("../class/user.class.php");
require_once("../class/gameaccount.class.php");
require_once("../data/gameaccount.DAO.php");

class USERDAO{
	public function getAllUsers(){
		$sqluseraccount = "SELECT * FROM useraccount ORDER BY userID ASC;";
        $sqlgameaccount = "SELECT * FROM gameaccounts ORDER BY userID, accountID ASC;";
        $sqlcharacter = "SELECT * FROM characters ORDER BY userID, accountID ASC;";
		$dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSetUseraccount = $dbh->query($sqluseraccount);
        $resultSetGameaccount = $dbh->query($sqlgameaccount);
        $resultSetCharacter = $dbh->query($sqlcharacter);
        $useraccountResults = $resultSetUseraccount->fetchAll(PDO::FETCH_ASSOC);
        $gameaccountResults = $resultSetGameaccount->fetchAll(PDO::FETCH_ASSOC);
        $characterResults = $resultSetCharacter->fetchAll(PDO::FETCH_ASSOC);
        $result = $this->createUserArray($useraccountResults, $gameaccountResults, $characterResults);
		return $result;
	}
	
	public function getUserById($id){
        $sqluseraccount = "SELECT * FROM useraccount WHERE userID = :id ORDER BY userID ASC;";
        $sqlgameaccount = "SELECT * FROM gameaccounts WHERE userID = :id ORDER BY userID, accountID ASC;";
        $sqlcharacter = "SELECT * FROM characters WHERE userID = :id ORDER BY userID, accountID ASC;";
		$dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqluseraccount);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $useraccountResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = $dbh->prepare($sqlgameaccount);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $gameaccountResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = $dbh->prepare($sqlcharacter);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $characterResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = $this->createUserArray($useraccountResults, $gameaccountResults, $characterResults)[0];
        return $result;
    }

    // To be fixed
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

    // To be fixed
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
        $sqluseraccount = 'SELECT * FROM useraccount WHERE userLogin = :name AND userPassword = :password LIMIT 1';
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqluseraccount);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $useraccountResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = $this->getUserById($useraccountResults[0]["userID"]);
        return $result;
	}

    //To be fixed
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

    // To be fixed
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

    public function payoutUserID($userID){
        $sql = "UPDATE useraccount SET payout = 0 WHERE userID = :userID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':userID', $userID);
        $binds = array(
            ":userID" => $userID,
        );
        $logdao = new LOGDAO();
        if ($stmt->execute()) { //1 if success, 0 if fail
            $logdao->logPreparedStatement('UPDATE', $stmt, $binds, 'SUCCESS');
            return true;
        }
        $logdao->logPreparedStatement('UPDATE', $stmt, $binds, 'FAILED');
        throw new Exception('Failed to payout userID. (' . $userID . ')');
        return false;
    }

    private function createUserArray($userAccountResults, $gameAccountResults, $characterResults){
        $result = array();
        $gameAccountPointer = 0;
        $characterPointer = 0;
        foreach($userAccountResults as $row){
            $user = USER::create($row['userID'], $row['userLogin'], $row['email'], $row['mailChar'],
                $row['password'], $row['roleLevel'], $row['forumAccount'], $row['payout']);
            $gameAccountList = array();
            while ($gameAccountResults[$gameAccountPointer]["userID"] <= $row["userID"] && isset($gameAccountResults[$gameAccountPointer])) {
                $gameAccount = GAMEACCOUNT::create($gameAccountResults[$gameAccountPointer]["userID"],
                    $gameAccountResults[$gameAccountPointer]["accountID"], $gameAccountResults[$gameAccountPointer]["cooldown"]);
                $characterList = array();
                while($characterResults[$characterPointer]["accountID"] == $gameAccountResults[$gameAccountPointer]["accountID"]) {
                    $character = CHARACTER::create($characterResults[$characterPointer]["accountID"],
                        $characterResults[$characterPointer]["charID"], $characterResults[$characterPointer]["charName"],
                        $characterResults[$characterPointer]["cooldown"], $characterResults[$characterPointer]["charClass"],
                        $characterResults[$characterPointer]["userID"]);
                    array_push($characterList, $character);
                    $characterPointer++;
                }
                $gameAccount->setCharacterList($characterList);
                array_push($gameAccountList, $gameAccount);
                $gameAccountPointer++;
            }
            $user->setGameAccountList($gameAccountList);
            array_push($result, $user);
        }
        return $result;
    }
}

?>