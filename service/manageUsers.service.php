<?php

class ManageUsersService
{
    public function updateUserByAJAX()
    {
        $dataService = new DataService();
        $idArray = explode("_", $_POST["id"]);
        if (isset($idArray[1])) {
            $userID = $idArray[1];
            /** @var EventType $user */
            $user = $dataService->getUserByUserID($userID);
            $newValue = $_POST["value"];
            if ($idArray[0] == "roleLevel") {
                $this->updateRoleLevelFromAJAX($user, $newValue);
            } else if ($idArray[0] == "GMT") {
                $this->updateGMTFromAJAX($user, $newValue);
            } else if ($idArray[0] == "email") {
                $this->updateEmailFromAJAX($user, $newValue);
            } else if ($idArray[0] == "mailChar") {
                $this->updateMailCharFromAJAX($user, $newValue);
            } else if ($idArray[0] == "password") {
                $this->updatePasswordFromAJAX($user, $newValue);
            }
        }
    }

    public function createUserByAJAX()
    {

    }

    private function updateRoleLevelFromAJAX($user, $roleLevel)
    {
        if (!$this->validRoleLevel($roleLevel)) {
            return;
        }
        /** @var User $user */
        $dataService = new DataService();
        $user->setRoleLevel($roleLevel);
        try {
            $dataService->updateUser($user);
        } catch (Exception $e) {
            print $e->getMessage();
            return;
        }
        /** @var User $updatedUser */
        $updatedUser = $dataService->getUserByUserID($user->getUserID());
        print $updatedUser->getRoleLevel();
    }

    private function validRoleLevel($roleLevel)
    {
        if (!is_numeric($roleLevel)) {
            return false;
        }
        return true;
    }

    private function updateGMTFromAJAX($user, $GMT)
    {
        if (!$this->validGMT($GMT)) {
            return;
        }
        /** @var User $user */
        $dataService = new DataService();
        $user->setGMT($GMT);
        try {
            $dataService->updateUser($user);
        } catch (Exception $e) {
            print $e->getMessage();
            return;
        }
        /** @var User $updatedUser */
        $updatedUser = $dataService->getUserByUserID($user->getUserID());
        print $updatedUser->getGMT();
    }

    private function validGMT($GMT)
    {
        if (!is_numeric($GMT)) {
            return false;
        }
        return true;
    }

    private function updateEmailFromAJAX($user, $email)
    {
        if (!$this->validEmail($email)) {
            return;
        }
        /** @var User $user */
        $dataService = new DataService();
        $user->setEmail($email);
        try {
            $dataService->updateUser($user);
        } catch (Exception $e) {
            print $e->getMessage();
            return;
        }
        /** @var User $updatedUser */
        $updatedUser = $dataService->getUserByUserID($user->getUserID());
        print $updatedUser->getEmail();
    }

    private function validEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    private function updateMailCharFromAJAX($user, $mailChar)
    {
        if (!$this->validMailChar($mailChar)) {
            return;
        }
        /** @var User $user */
        $dataService = new DataService();
        $user->setMailChar($mailChar);
        try {
            $dataService->updateUser($user);
        } catch (Exception $e) {
            print $e->getMessage();
            return;
        }
        /** @var User $updatedUser */
        $updatedUser = $dataService->getUserByUserID($user->getUserID());
        print $updatedUser->getMailChar();
    }

    private function validMailChar($mailChar)
    {
        if (empty($mailChar)) {
            return false;
        }
        return true;
    }

    private function updatePasswordFromAJAX($user, $password)
    {
        if (!$this->validPassword($password)) {
            return;
        }
        /** @var User $user */
        $dataService = new DataService();
        $user->setUserPassword(md5($password));
        try {
            $dataService->updateUser($user);
        } catch (Exception $e) {
            print $e->getMessage();
            return;
        }
        /** @var User $updatedUser */
        $updatedUser = $dataService->getUserByUserID($user->getUserID());
        $updatedPassword = $updatedUser->getUserPassword();
        if ($updatedPassword != md5($password)) {
            print "Password change FAILED!";
        } else {
            print "Password changed!";
        }
    }

    private function validPassword($password)
    {
        return true;
    }
}