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
	
	public function getUserByID($id){
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

    public function addUser($userLogin, $userPassword, $roleLevel, $email, $mailChar, $forumAccount)
    {
        $sql = "INSERT INTO useraccount VALUES (NULL, :userLogin, :userPassword, :roleLevel, :email, :mailChar, :forumAccount, 0);";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':userLogin', $userLogin);
        $stmt->bindParam(':userPassword', $userPassword);
        $stmt->bindParam(':roleLevel', $roleLevel);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mailChar', $mailChar);
        $stmt->bindParam(':forumAccount', $forumAccount);
        $binds = array(
            ":username" => $userLogin,
            ":userPassword" => $userPassword,
            ":roleLevel" => $roleLevel,
            ":email" => $email,
            ":mailChar" => $mailChar,
            ":forumAccount" => $forumAccount,
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

    public function updateUser($user)
    {
        /** @var $user USER */
        $sql = 'UPDATE useraccount
                    SET userLogin = :userLogin,
                     userPassword = :userPassword,
                     roleLevel = :roleLevel,
                     email = :email,
                     mailChar = :mailChar,
                     forumAccount = :forumAccount,
                     payout = :payout
                    WHERE userID = :userID;';
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':userLogin', $user->getUserLogin());
        $stmt->bindParam(':userPassword', $user->getUserPassword());
        $stmt->bindParam(':roleLevel', $user->getRoleLevel());
        $stmt->bindParam(':email', $user->getEmail());
        $stmt->bindParam(':mailChar', $user->getMailChar());
        $stmt->bindParam(':forumAccount', $user->getForumAccount());
        $stmt->bindParam(':payout', $user->getPayout());
        $stmt->bindParam(':userID', $user->getUserID());
        $binds = array(
            ":userLogin" => $user->getUserLogin(),
            ":userPassword" => $user->getUserPassword(),
            ":roleLevel" => $user->getRoleLevel(),
            ":email" => $user->getEmail(),
            ":mailChar" => $user->getMailChar(),
            ":forumAccount" => $user->getForumAccount(),
            ":payout" => $user->getPayout(),
            ":userID" => $user->getUserID(),
        );
        $logdao = new LOGDAO();
        if ($stmt->execute()) { //1 if success, 0 if fail
            $logdao->logPreparedStatement('UPDATE', $stmt, $binds, 'SUCCESS');
            $dao = new USERDAO();
            return $dao->getUserById($user->getUserID());
        }
        $logdao->logPreparedStatement('UPDATE', $stmt, $binds, 'FAILED');
        throw new Exception('Failed to update user. (' . $user->getUserLogin() . ')');
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
                $row['userPassword'], $row['roleLevel'], $row['forumAccount'], $row['payout']);
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