<?php
require_once("../service/page.service.php");
require_once("../service/users.service.php");
require_once("../service/events.service.php");

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION["userID"]);
    $notification = array(
        'type' => 'info',
        'title' => 'You will be missed!',
        'message' => 'You have logged out successfully.',
    );
}

if (isset($_GET['action']) && $_GET['action'] === 'login' && $_POST['name'] && $_POST['password']) {
    $userservice = new userservice();
    $user = $userservice->getUserByNameAndPassword($_POST['name'], md5($_POST['password']));
    if ($user) {
        $_SESSION["userID"] = $user->getId();
        $notification = array(
            'type' => 'success',
            'title' => 'Nice!',
            'message' => 'You have logged in successfully.',
        );
    } else {
        $notification = array(
            'type' => 'danger',
            'title' => 'Error',
            'message' => 'The name or password you entered is incorrect.',
        );
    }
}

$pageservice = new PAGESERVICE();
$currentPageID = "home";

if (isset($_SESSION["userID"])) {
    $sessionUser = $pageservice->whoIsSessionUser($_SESSION["userID"]);
    $navbarlinks = $pageservice->generateNavLinksForUser($_SESSION["userID"]);
} else {
    $navbarlinks = $pageservice->generateNavLinksForUser();
}
$usefulllinks = $pageservice->generateUsefulLinks(5);
$featuredlinks = $pageservice->generateFeaturedLinks(5);

echo "<script type='text/javascript'>alert('$message');</script>";
include("../view/home.view.php");