<?php
require_once("../data/user.DAO.php");

class UserService
{
    public function getAllUsers()
    {
        $userDAO = new UserDAO();
        return $userDAO->getAllUsers();
    }

    public function getUserByUserID($userID)
    {
        $userDAO = new UserDAO();
        return $userDAO->getUserByUserID($userID);
    }

    public function createUser($user)
    {
        $userDAO = new UserDAO();
        return $userDAO->createUser($user);
    }

    public function updateUser($user)
    {
        $userDAO = new UserDAO();
        return $userDAO->updateUser($user);
    }

    public function getUserIDByLoginAndPassword($userLogin, $userPassword)
    {
        $userDAO = new UserDAO();
        return $userDAO->getUserIDByLoginAndPassword($userLogin, $userPassword);
    }
}
