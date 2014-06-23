<?php
require_once("../service/data.service.php");
require_once("../class/forumUser.class.php");

class RegisterService
{
    public function validateRegistration($registrationData)
    {
        $report = array();
        if (!$this->isValidName($registrationData['name'])) {
            $reportEntry = array(
                'Status' => 'Error',
                'Message' => 'Username \'' . $registrationData['name'] . '\' is already in use.'
            );
            $report[] = $reportEntry;
        }
        if (!$this->isValidAegisName($registrationData['aegisName'])) {
            $reportEntry = array(
                'Status' => 'Error',
                'Message' => $registrationData['aegisName'] . ' is not a name registered at Aegis Forums.'
            );
            $report[] = $reportEntry;
        }
        if (!$this->isValidPasswords($registrationData['password'], $registrationData['retypePassword'])) {
            $reportEntry = array(
                'Status' => 'Error',
                'Message' => 'Passwords do not match.'
            );
            $report[] = $reportEntry;
        }

        if (!$this->isValidEmail($registrationData['email'])) {
            $reportEntry = array(
                'Status' => 'Error',
                'Message' => 'Email is not valid.'
            );
            $report[] = $reportEntry;
        }

        if (!$this->isValidTimezone($registrationData['GMT'])) {
            $reportEntry = array(
                'Status' => 'Error',
                'Message' => 'Timezone is not valid.'
            );
            $report[] = $reportEntry;
        }

        if (sizeof($report) == 0) {
            try {
                $this->registerNewUser($registrationData);
            } catch (Exception $e) {
                $reportEntry = array(
                    'Status' => 'Error',
                    'Message' => 'Failed to register user'
                );
                $report[] = $reportEntry;
            }
        }
        return $report;
    }

    public function createFeedbackNotification($report)
    {
        foreach ($report as $reportEntry) {
            if ($reportEntry['Status'] == 'Error') {
                $feedback['type'] = 'danger';
                $feedback['title'] = 'Error';
                if (!isset($feedback['message'])) {
                    $feedback['message'] = '';
                }
                $feedback['message'] .= $reportEntry['Message'] . ' ';
            }
        }
        if (empty($feedback)) {
            $feedback = array(
                'type' => 'success',
                'title' => 'Success',
                'message' => 'You have been registered successfully. '
            );
        }
        return $feedback;
    }

    private function registerNewUser($registrationData)
    {
        $dataService = new DataService();
        $user = new User(NULL, $registrationData['name'], $registrationData['email'], $registrationData['mailChar'], md5($registrationData['password']),
            0, $registrationData['aegisName'], 0, $registrationData['GMT']);
        $dataService->createUser($user);
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

    private function isValidName($name)
    {
        if (!$this->nameAlreadyExistsInDatabase($name)) {
            return true;
        }
        return false;
    }

    private function nameAlreadyExistsInDatabase($userLogin)
    {
        try {
            $dataService = new DataService();
            $existingUser = $dataService->getUserByUserLogin($userLogin);
        } catch (Exception $e) {
            return false;
        }

        if (is_null($existingUser)) {
            return false;
        } else {
            return true;
        }
    }

    private function isValidAegisName($name)
    {
        $forumAdmin = new ForumUser;
        $forumAdmin->logIn();
        return $forumAdmin->userExists($name);
    }

    private function isValidPasswords($password, $password2)
    {
        if ($this->passwordsAreIdentical($password, $password2)) {
            return true;
        }
        return false;
    }

    private function passwordsAreIdentical($password1, $password2)
    {
        if ($password1 === $password2) {
            return true;
        }
        return false;
    }
}