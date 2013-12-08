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
            'type' => 'confirmation',
            'message' => 'Successfully changed user. (' . $sessionUser->getName() . ')',
        );
    } catch (Exception $e) {
        $notification = array(
            'type' => 'error',
            'message' => $e->getMessage(),
        );
    }
}

if(isset($_POST) && isset($_GET['action']) && $_GET['action'] == 'password'){
    if($_POST['newpassword'] != $_POST['confirmpassword']){
        $notification = array(
            'type' => 'error',
            'message' => 'New Passwords don\'t match.',
        );
    } else {
        $userservice = new userservice();
        $user = $userservice->getUserByNameAndPassword($sessionUser->getName(), md5($_POST['oldpassword']));
        if ($user) {
            try {
                $user = $userservice->editPasswordOfUser($sessionUser->getId(), md5($_POST['newpassword']));
                $notification = array(
                    'type' => 'confirmation',
                    'message' => 'Successfully changed password.',
                );
            } catch (Exception $e) {
                $notification = array(
                    'type' => 'error',
                    'message' => $e->getMessage(),
                );
            }
        } else {
            $notification = array(
                'type' => 'error',
                'message' => 'Wrong password.',
            );
        }
    }
}

$usefulllinks = $pageservice->generateUsefulLinks(5);
$featuredlinks = $pageservice->generateFeaturedLinks(5);

$user = $userservice->getUserByID($_SESSION["userID"]);
include("../view/settings.view.php");