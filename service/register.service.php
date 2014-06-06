<?php
require_once("../data/user.DAO.php");
require_once("../data/register.DAO.php");
require_once("../class/forumUser.class.php");

class RegisterService
{
    public function validateRegistration($registrationData)
    {
        $report = array();
        if (!$this->isValidName($registrationData['name'])) {
            $reportEntry = array(
                'Status' => 'Error',
                'Message' => 'Username \''. $registrationData['name']. '\' is already in use.'
            );
            $report[] = $reportEntry;
        }
        if (!$this->isValidAegisName($registrationData['aegisName'])) {
            $reportEntry = array(
                'Status' => 'Error',
                'Message' => $registrationData['aegisName']. ' is not a name registered at Aegis Forums.'
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
        return $report;
    }

    public function createFeedbackNotification($report){
        foreach($report as $reportEntry){
            if($reportEntry['Status'] == 'Error'){
                $feedback['type'] = 'danger';
                $feedback['title'] = 'Error';
                $feedback['message'] .= $reportEntry['Message'] .' ';
            }
        }
        if(empty($feedback)){
            $feedback = array(
                'type' => 'success',
                'title' => 'Success',
                'message' => 'You have been registered successfully. '
            );
        }
        return $feedback;
    }

    private function isValidName($name)
    {
        if (!$this->nameAlreadyExistsInDatabase($name)) {
            return true;
        }
        return false;
    }

    private function nameAlreadyExistsInDatabase($name)
    {
        try {
            $userDAO = new USERDAO();
            $userDAO->getUserByName($name);
            return true;
        } catch (noUserFoundException $e) {
            return false;
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
        if($this->passwordsAreIdentical($password, $password2)){
            return true;
        }
        return false;
    }

    private function passwordsAreIdentical($password1, $password2){
        if($password1 === $password2){
            return true;
        }
        return false;
    }
}