<?php
require_once("../data/user.DAO.php");

class userService
{
    public function listAllUsers()
    {
        $userDAO = new USERDAO();
        return $userDAO->getAllUsers();
    }

    public function getUserByID($userID)
    {
        $userDAO = new USERDAO();
        return $userDAO->getUserByID($userID);
    }

    public function addUser($userLogin, $userPassword, $roleLevel, $email, $mailChar, $forumAccount)
    {
        $userDAO = new USERDAO();
        $user = $userDAO->addUser($userLogin, $userPassword, $roleLevel, $email, $mailChar, $forumAccount);
        return $user;
    }

    public function updateUser($user)
    {
        $userDAO = new USERDAO();
        return $userDAO->updateUser($user);
    }

    public function getUserByNameAndPassword($name, $password)
    {
        $userDAO = new USERDAO();
        return $userDAO->getUserByNameAndPassword($name, $password);
    }

    public function payoutByUser($user)
    {
        /** @var $user USER */
        $userDAO = new USERDAO();
        return $userDAO->payoutUserID($user->getUserID());
    }
}
