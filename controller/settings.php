<?php
require_once("../service/page.service.php");
require_once("../service/users.service.php");

$pageservice = new PAGESERVICE();
$currentPageID = "Settings";
if (isset($_SESSION["userID"])) {
    $sessionUser = $pageservice->whoIsSessionUser($_SESSION["userID"]);
    $navbarlinks = $pageservice->generateNavLinksForUser($_SESSION["userID"]);
} else {
    header("location: ./home.php");
}

$userservice = new userservice();
if(isset($_POST) && isset($_GET['action']) && $_GET['action'] == 'edit'){
    try {
        $user = $userservice->editUser($sessionUser->getId(), $_POST['mailname'], $_POST['forumname'], $_POST['email']);
        $notification = array(
            'type' => 'success',
            'title' => 'Confirmation',
            'message' => 'Successfully changed user. (' . $sessionUser->getName() . ')',
        );
    } catch (Exception $e) {
        $notification = array(
            'type' => 'danger',
            'title' => 'Error',
            'message' => $e->getMessage(),
        );
    }
}

if(isset($_POST) && isset($_GET['action']) && $_GET['action'] == 'password'){
    if($_POST['newpassword'] != $_POST['confirmpassword']){
        $notification = array(
            'type' => 'danger',
            'title' => 'Error',
            'message' => 'New Passwords don\'t match.',
        );
    } else {
        $userservice = new userservice();
        $user = $userservice->getUserByNameAndPassword($sessionUser->getName(), md5($_POST['oldpassword']));
        if ($user) {
            try {
                $user = $userservice->editPasswordOfUser($sessionUser->getId(), md5($_POST['newpassword']));
                $notification = array(
                    'type' => 'success',
                    'title' => 'Confirmation',
                    'message' => 'Successfully changed password.',
                );
            } catch (Exception $e) {
                $notification = array(
                    'type' => 'danger',
                    'title' => 'Error',
                    'message' => $e->getMessage(),
                );
            }
        } else {
            $notification = array(
                'type' => 'danger',
                'title' => 'Error',
                'message' => 'Wrong password.',
            );
        }
    }
}

$user = $userservice->getUserByID($_SESSION["userID"]);
include("../view/settings.view.php");