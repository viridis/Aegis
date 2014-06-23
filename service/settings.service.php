<?php

class SettingsService
{
    private function isValidUpdateInfo()
    {
        if (!$this->isValidMailChar($_POST["mailChar"])) {
            return false;
        }

        if (!$this->isValidEmail($_POST["email"])) {
            return false;
        }

        if (!$this->isValidTimeZone($_POST["GMT"])) {
            return false;
        }
        return true;
    }

    private function isValidMailChar($mailChar)
    {
        return true;
    }

    private function isValidEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    private function isValidTimeZone($GMT)
    {
        if (!is_numeric($GMT)) {
            return false;
        }
        return true;
    }

    public function updateUserInfo()
    {
        if (!$this->isValidUpdateInfo()) {
            return false;
        }
        $dataService = new DataService();
        /** @var User $user */
        $user = $dataService->getUserByUserID($_SESSION["userID"]);
        $user->setMailChar($_POST["mailChar"]);
        $user->setGMT($_POST["GMT"]);
        $user->setEmail($_POST["email"]);
        try {
            $dataService->updateUser($user);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    public function updateUserPassword()
    {
        $dataService = new DataService();
        /** @var User $user */
        $user = $dataService->getUserByUserID($_SESSION["userID"]);

        if (!$this->currentPasswordMatch($user)) {
            return false;
        }

        if (!$this->newPasswordsMatch()) {
            return false;
        }

        $user->setUserPassword(md5($_POST["newpassword"]));
        try {
            $dataService->updateUser($user);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    private function currentPasswordMatch($user)
    {
        /** @var User $user */
        if ($user->getUserPassword() != md5($_POST["oldpassword"])) {
            return false;
        } else {
            return true;
        }
    }

    private function newPasswordsMatch()
    {
        if ($_POST["newpassword"] == $_POST["confirmpassword"]) {
            return true;
        } else {
            return false;
        }
    }
}