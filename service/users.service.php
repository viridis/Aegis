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

    public static function getUserByID($id)
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

    public function editUser($id, $mailName, $forumName, $email)
    {
        $userdao = new USERDAO();
        $user = $userdao->editUser($id, $mailName, $forumName, $email);
        return $user;
    }

    public function editPasswordOfUser($id, $password){
        $userdao = new USERDAO();
        $user = $userdao->editPasswordOfUser($id, $password);
        return $user;
    }
}

?>