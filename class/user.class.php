<?php

class User
{
    private static $idList = array();

    private $userID;
    private $userLogin;
    private $email;
    private $mailChar;
    private $password;
    private $roleLevel;
    private $forumAccount;
    private $payout;
    private $gmt;

    // Associated fields
    private $gameAccountContainer = array();

    public function __construct($userID, $userLogin, $email, $mailChar, $password, $roleLevel, $forumAccount, $payout, $gmt)
    {
        $this->userID = $userID;
        $this->userLogin = $userLogin;
        $this->email = $email;
        $this->mailChar = $mailChar;
        $this->roleLevel = $roleLevel;
        $this->forumAccount = $forumAccount;
        $this->payout = $payout;
        $this->password = $password;
        $this->gmt = $gmt;
    }

    public static function create($userID, $userLogin, $email, $mailChar, $password, $roleLevel, $forumAccount, $payout, $gmt)
    {
        if (!isset(self::$idList[$userID])) {
            self::$idList[$userID] = new User($userID, $userLogin, $email, $mailChar, $password, $roleLevel, $forumAccount, $payout, $gmt);
        }
        return self::$idList[$userID];
    }

    public function clearUser()
    {
        self::$idList = array();
    }

    public function getUserID()
    {
        return $this->userID;
    }

    public function getUserLogin()
    {
        return $this->userLogin;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getMailChar()
    {
        return $this->mailChar;
    }

    public function getUserPassword()
    {
        return $this->password;
    }

    public function getRoleLevel()
    {
        return $this->roleLevel;
    }

    public function getForumAccount()
    {
        return $this->forumAccount;
    }

    public function getPayout()
    {
        return $this->payout;
    }

    public function getGameAccountContainer()
    {
        return $this->gameAccountContainer;
    }

    public function getGMT()
    {
        return $this->gmt;
    }

    public function setGameAccountContainer($gameAccountContainer)
    {
        $this->gameAccountContainer = $gameAccountContainer;
    }

    public function setPayout($payout)
    {
        $this->payout = $payout;
    }

    public function setMailChar($mailChar)
    {
        $this->mailChar = $mailChar;
    }

    public function setUserPassword($userPassword)
    {
        $this->password = $userPassword;
    }

    public function setGMT($gmt)
    {
        $this->gmt = $gmt;
    }

    public function incrementPayout($payoutIncrement)
    {
        $this->payout += $payoutIncrement;
    }

    public function setRoleLevel($roleLevel)
    {
        $this->roleLevel = $roleLevel;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setForumAccount($forumAccount)
    {
        $this->forumAccount = $forumAccount;
    }
}