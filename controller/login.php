<?php
require_once("../service/page.service.php");
require_once("../service/users.service.php");

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION["userID"]);
    header('location: ./home.php?action=loggedOut');
}

if (isset($_GET['action']) && $_GET['action'] === 'login') {
    $userservice = new userservice();
    $user = $userservice->getUserByNameAndPassword($_POST['name'], md5($_POST['password']));
    if ($user) {
        $_SESSION["userID"] = $user->getId();
        header('location: ./home.php?action=loggedIn');
    }
    $notification = array(
        'type' => 'error',
        'message' => 'The name or password you entered is not correct.',
    );
}

$pageservice = new PAGESERVICE();
$currentPageID = "Login";
if ($_SESSION["userID"]) {
    $navbarlinks = $pageservice->generateNavLinks();
} else {
    $navbarlinks = $pageservice->generateNavLinks('user');
}
$usefulllinks = $pageservice->generateUsefulLinks(5);
$featuredlinks = $pageservice->generateFeaturedLinks(5);
include("../view/login.view.php");
?>