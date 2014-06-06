<?php
require_once("../service/page.service.php");
require_once("../service/users.service.php");

if (!$_SESSION["userID"]) {
    header("location: ./home.php");
}
$pageservice = new PAGESERVICE();
$currentPageID = "Manage Users";
if (isset($_SESSION["userID"])) {
    $sessionUser = $pageservice->whoIsSessionUser($_SESSION["userID"]);
    $navbarlinks = $pageservice->generateNavLinksForUser($_SESSION["userID"]);
} else {
    $navbarlinks = $pageservice->generateNavLinksForUser();
}

$userservice = new userservice();
if (isset($_POST["addUser"])) {
    try {
        $user = $userservice->addUser($_POST["addUser"]);
        $notification = array(
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Successfully added user. (' . $user->getName() . ')',
        );
    } catch (Exception $e) {
        $notification = array(
            'type' => 'error',
            'message' => $e->getMessage(),
        );
    }
}

if (isset($_GET['editUser']) && is_numeric($_GET['editUser'])) {
    try {
        $user = $userservice->getUserById($_GET['editUser']);
        $result['action'] = 'requestUser';
        $result['user']['id'] = $user->getId();
        $result['user']['name'] = $user->getName();
        $result['user']['mailName'] = $user->getMailName();
        print(json_encode($result));
        exit();
    } catch (Exception $e) {
        print($e);
        exit();
    }
}

if (isset($_POST['editUserID']) && isset($_POST['editUserName']) && isset($_POST['editUserMailName']) && is_numeric($_POST['editUserID'])) {
    try {
        $user = $userservice->updateUser($_POST['editUserID'], $_POST['editUserName'], $_POST['editUserMailName']);
        $notification = array(
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Successfully changed user. (' . $user->getName() . ')',
        );
    } catch (Exception $e) {
        $notification = array(
            'type' => 'danger',
            'title' => 'Error',
            'message' => $e->getMessage(),
        );
    }
}

$userlist = $userservice->listAllUsers();
include("../view/users.view.php");
?>