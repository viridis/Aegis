<?php
require_once("../service/page.service.php");
require_once("../service/data.service.php");

$pageService = new PageService();
$dataService = new DataService();

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION["userID"]);
    $notification = array(
        'type' => 'info',
        'title' => 'You will be missed!',
        'message' => 'You have logged out successfully.',
    );
}

if (isset($_GET['action']) && $_GET['action'] === 'login' && $_POST['name'] && $_POST['password']) {
    $user = $dataService->getUserByLoginAndPassword($_POST['name'], md5($_POST['password']));
    /** @var $user User */
    if ($user) {
        $_SESSION["userID"] = $user->getUserID();
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
$currentPageID = "home";

if (isset($_SESSION["userID"])) {
    $sessionUser = $pageService->whoIsSessionUser($_SESSION["userID"]);
    $navBarLinks = $pageService->generateNavLinksForUser($_SESSION["userID"]);
} else {
    $navBarLinks = $pageService->generateNavLinksForUser();
}
$usefulLinks = $pageService->generateUsefulLinks(5);
$featuredLinks = $pageService->generateFeaturedLinks(5);

var_dump($dataService->getAllUserInfo());
include("../view/home.view.php");