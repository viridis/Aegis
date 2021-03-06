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
            } else if ($idArray[0] == "forumAccount") {
                $this->updateForumAccountFromAJAX($user, $newValue);
            }
        }
    }

    public function createUserByAJAX()
    {
        if (!is_string($_POST["newUserLogin"])) {
            return;
        }
        $dataService = new DataService();
        try {
            $user = new User(NULL, $_POST["newUserLogin"], "Not Set", "Not Set", "password", 0, "Not Set", 0, 0);
            $userID = $dataService->createUser($user);
        } catch (Exception $e) {
            print $e->getMessage();
            return;
        }
        /** @var User $newUser */
        $newUser = $dataService->getUserByUserID($userID);
        print '<tr><td>' . $newUser->getUserLogin() . '</td>';
        print '<td class="edit" id="roleLevel_' . $newUser->getUserID() . '">' . $newUser->getRoleLevel() . '</td>';
        print '<td class="edit" id="GMT_' . $newUser->getUserID() . '">' . $newUser->getGMT() . '</td>';
        print '<td class="edit" id="email_' . $newUser->getUserID() . '">' . $newUser->getEmail() . '</td>';
        print '<td class="edit" id="mailChar_' . $newUser->getUserID() . '">' . $newUser->getMailChar() . '</td>';
        print '<td class="edit" id="password_' . $newUser->getUserID() . '">Click to Change</td>';
        print '<td class="edit" id="forumAccount_' . $newUser->getUserID() . '">' . $newUser->getForumAccount() . '</td></tr>';
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
        if (empty($password)) {
            return false;
        }
        return true;
    }

    private function updateForumAccountFromAJAX($user, $forumAccount)
    {
        if (!$this->validForumAccount($forumAccount)) {
            return;
        }
        /** @var User $user */
        $dataService = new DataService();
        $user->setForumAccount($forumAccount);
        try {
            $dataService->updateUser($user);
        } catch (Exception $e) {
            print $e->getMessage();
            return;
        }
        /** @var User $updatedUser */
        $updatedUser = $dataService->getUserByUserID($user->getUserID());
        print $updatedUser->getForumAccount();
    }

    private function validForumAccount($forumAccount)
    {
        if (empty($forumAccount)) {
            return false;
        }
        return true;
    }
}