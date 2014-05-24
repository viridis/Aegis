<?php
require_once("../data/dbconfig.DAO.php");
require_once("../data/log.DAO.php");
require_once("../data/character.DAO.php");
require_once("../class/user.class.php");
require_once("../data/gameAccount.DAO.php");

class UserDAO
{
    public function getAllUsers()
    {
        $sqlUserAccount = "SELECT * FROM useraccount ORDER BY userID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSetUserAccount = $dbh->query($sqlUserAccount);
        $userAccountResults = $resultSetUserAccount->fetchAll(PDO::FETCH_ASSOC);
        return $userAccountResults;
    }

    public function getUserByUserID($userID)
    {
        $sqlUserAccount = "SELECT * FROM useraccount WHERE userID = :id ORDER BY userID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlUserAccount);
        $stmt->bindParam(':id', $userID);
        $stmt->execute();
        $userAccountResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $userAccountResults;
    }

    public function createUser($user)
    {
        /** @var USER $user */
        $sql = "INSERT INTO useraccount VALUES (NULL, :userLogin, :userPassword, :roleLevel, :email, :mailChar, :forumAccount, 0);";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':userLogin', $user->getUserLogin());
        $stmt->bindParam(':userPassword', $user->getUserPassword());
        $stmt->bindParam(':roleLevel', $user->getRoleLevel());
        $stmt->bindParam(':email', $user->getEmail());
        $stmt->bindParam(':mailChar', $user->getMailChar());
        $stmt->bindParam(':forumAccount', $user->getForumAccount());
        $binds = array(
            ":userLogin" => $user->getUserLogin(),
            ":userPassword" => $user->getUserPassword(),
            ":roleLevel" => $user->getRoleLevel(),
            ":email" => $user->getEmail(),
            ":mailChar" => $user->getMailChar(),
            ":forumAccount" => $user->getForumAccount(),
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) { //1 if success, 0 if fail
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return true;
        }
        $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
        throw new Exception('Failed to add user. (' . $user->getUserLogin() . ')');
    }

    public function updateUser($user)
    {
        /** @var $user User */
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
        $logDAO = new LogDAO();
        if ($stmt->execute()) { //1 if success, 0 if fail
            $logDAO->logPreparedStatement('UPDATE', $stmt, $binds, 'SUCCESS');
            return true;
        }
        $logDAO->logPreparedStatement('UPDATE', $stmt, $binds, 'FAILED');
        throw new Exception('Failed to update user. (' . $user->getUserLogin() . ')');
    }

    public function getUserIDByLoginAndPassword($userLogin, $userPassword)
    {
        $sqlUserAccount = 'SELECT * FROM useraccount WHERE userLogin = :userLogin AND userPassword = :userPassword LIMIT 1;';
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlUserAccount);
        $stmt->bindParam(':userLogin', $userLogin);
        $stmt->bindParam(':userPassword', $userPassword);
        $stmt->execute();
        $userAccountResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $userAccountResults[0]["userID"];
    }
}
