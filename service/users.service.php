<?php
require_once("../data/user.DAO.php");

class userservice
{
    public function listAllUsers()
    {
        $userdao = new USERDAO();
        $userlist = $userdao->getAllUsers();
        return $userlist;
    }

    public function getUserByID($id)
    {
        $userdao = new USERDAO();
        $user = $userdao->getUserById($id);
        return $user;
    }

    public function addUser($name)
    {
        $userdao = new USERDAO();
        $user = $userdao->addUser($name);
        return $user;
    }

    public function updateUser($id, $name, $mailName)
    {
        $userdao = new USERDAO();
        $user = $userdao->updateUser($id, $name, $mailName);
        return $user;
    }

    public function getUserByNameAndPassword($name, $password)
    {
        $userdao = new USERDAO();
        $user = $userdao->getUserByNameAndPassword($name, $password);
        return $user;
    }
}

?>