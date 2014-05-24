<?php
require_once("../data/user.DAO.php");

class userservice
{
    public function listAllUsers()
    {
        $userdao = new USERDAO();
        return $userdao->getAllUsers();
    }

    public static function getUserByID($id)
    {
        $userdao = new USERDAO();
        $user = $userdao->getUserByID($id);
        return $user;
    }

    public function addUser($userLogin, $userPassword, $roleLevel, $email, $mailChar, $forumAccount)
    {
        $userdao = new USERDAO();
        $user = $userdao->addUser($userLogin, $userPassword, $roleLevel, $email, $mailChar, $forumAccount);
        return $user;
    }

    public function updateUser($user)
    {
        $userdao = new USERDAO();
        return $userdao->updateUser($user);
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

    public function payoutByUserID($userID){
        $userdao = new USERDAO();
        return $userdao->payoutUserID($userID);
    }
}

?>