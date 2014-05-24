<?php
require_once("../service/page.service.php");
require_once("../service/user.service.php");

$pageservice = new PageService();
$currentPageID = "Settings";
if (isset($_SESSION["userID"])) {
    $sessionUser = $pageservice->whoIsSessionUser($_SESSION["userID"]);
    $navbarlinks = $pageservice->generateNavLinksForUser($_SESSION["userID"]);
} else {
    header("location: ./home.php");
}

$userservice = new UserService();
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
        $userservice = new UserService();
        $user = $userservice->getUserIDByLoginAndPassword($sessionUser->getName(), md5($_POST['oldpassword']));
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

$usefulllinks = $pageservice->generateUsefulLinks(5);
$featuredlinks = $pageservice->generateFeaturedLinks(5);

$user = $userservice->getUserByUserID($_SESSION["userID"]);
include("../view/settings.view.php");