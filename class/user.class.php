<?php

class USER
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

    // Associated fields
    private $gameAccountContainer;

    function __construct($userID, $userLogin, $email, $mailChar, $password, $roleLevel, $forumAccount, $payout)
    {
        $this->userID = $userID;
        $this->userLogin = $userLogin;
        $this->email = $email;
        $this->mailChar = $mailChar;
        $this->roleLevel = $roleLevel;
        $this->forumAccount = $forumAccount;
        $this->payout = $payout;
        $this->password = $password;
    }

    public static function create($userID, $userLogin, $email, $mailChar, $password, $roleLevel, $forumAccount, $payout)
    {
        if (!isset(self::$idList[$userID])) {
            self::$idList[$userID] = new USER($userID, $userLogin, $email, $mailChar, $password, $roleLevel, $forumAccount, $payout);
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

    public function setGameAccountContainer($gameAccountContainer)
    {
        $this->gameAccountContainer = $gameAccountContainer;
    }

    public function setPayout($payout)
    {
        $this->payout = $payout;
    }
}

?>