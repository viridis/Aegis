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
        $sql = "INSERT INTO useraccount VALUES (NULL, :userLogin, :userPassword, :roleLevel, :email, :mailChar, :forumAccount, 0, :gmt);";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $userLogin = $user->getUserLogin();
        $stmt->bindParam(':userLogin', $userLogin);
        $userPassword = $user->getUserPassword();
        $stmt->bindParam(':userPassword', $userPassword);
        $roleLevel = $user->getRoleLevel();
        $stmt->bindParam(':roleLevel', $roleLevel);
        $email = $user->getEmail();
        $stmt->bindParam(':email', $email);
        $mailChar = $user->getMailChar();
        $stmt->bindParam(':mailChar', $mailChar);
        $forumAccount = $user->getForumAccount();
        $stmt->bindParam(':forumAccount', $forumAccount);
        $gmt = $user->getGMT();
        $stmt->bindParam(':gmt', $gmt);
        $binds = array(
            ":userLogin" => $userLogin,
            ":userPassword" => $userPassword,
            ":roleLevel" => $roleLevel,
            ":email" => $email,
            ":mailChar" => $mailChar,
            ":forumAccount" => $forumAccount,
            ":gmt" => $gmt
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) { //1 if success, 0 if fail
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return true;
        }
        $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
        throw new Exception('Failed to add user. (' . $userLogin . ')');
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
                     payout = :payout,
                     gmt = :gmt
                    WHERE userID = :userID;';
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $userLogin = $user->getUserLogin();
        $stmt->bindParam(':userLogin', $userLogin);
        $userPassword = $user->getUserPassword();
        $stmt->bindParam(':userPassword', $userPassword);
        $roleLevel = $user->getRoleLevel();
        $stmt->bindParam(':roleLevel', $roleLevel);
        $email = $user->getEmail();
        $stmt->bindParam(':email', $email);
        $mailChar = $user->getMailChar();
        $stmt->bindParam(':mailChar', $mailChar);
        $forumAccount = $user->getForumAccount();
        $stmt->bindParam(':forumAccount', $forumAccount);
        $payout = $user->getPayout();
        $stmt->bindParam(':payout', $payout);
        $userID = $user->getUserID();
        $stmt->bindParam(':userID', $userID);
        $gmt = $user->getGMT();
        $stmt->bindParam(':gmt', $gmt);
        $binds = array(
            ":userLogin" => $userLogin,
            ":userPassword" => $userPassword,
            ":roleLevel" => $roleLevel,
            ":email" => $email,
            ":mailChar" => $mailChar,
            ":forumAccount" => $forumAccount,
            ":payout" => $payout,
            ":userID" => $userID,
            ":gmt" => $gmt
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) { //1 if success, 0 if fail
            $logDAO->logPreparedStatement('UPDATE', $stmt, $binds, 'SUCCESS');
            return true;
        }
        $logDAO->logPreparedStatement('UPDATE', $stmt, $binds, 'FAILED');
        throw new Exception('Failed to update user. (' . $userLogin . ')');
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
        if (isset($userAccountResults[0]))
            return $userAccountResults[0]["userID"];
    }
}
