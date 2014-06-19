<?php
require_once("../service/page.service.php");
require_once("../service/data.service.php");
require_once("../service/manageUsers.service.php");

if (!$_SESSION["userID"]) {
    header("location: ./home.php");
}
$pageservice = new PageService();
$dataService = new DataService();
$manageUsersService = new ManageUsersService();
$currentPageID = "Manage Users";
if (isset($_SESSION["userID"])) {
    $sessionUser = $pageservice->whoIsSessionUser($_SESSION["userID"]);
    $navBarLinks = $pageservice->generateNavLinksForUser($_SESSION["userID"]);
} else {
    $navBarLinks = $pageservice->generateNavLinksForUser();
}
$usefulLinks = $pageservice->generateUsefulLinks(5);
$featuredLinks = $pageservice->generateFeaturedLinks(5);

if (isset($_POST["id"]) && isset($_POST["value"])) {
    $manageUsersService->updateUserByAJAX();
    exit();
}

if (isset($_POST["newUserLogin"])){
    $manageUsersService->createUserByAJAX();
    exit();
}

$userContainer = $dataService->getAllUserInfo();
include("../view/users.view.php");