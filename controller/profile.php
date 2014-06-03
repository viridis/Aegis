<?php
require_once("../service/page.service.php");
require_once("../service/data.service.php");
require_once("../service/profile.service.php");

$pageService = new PageService();
$dataService = new DataService();

if (!$pageService->authorizedUser(1)) {
    header("location: ./home.php");
}

$currentPageID = "Profile";
if (isset($_SESSION["userID"])) {
    $sessionUser = $pageService->whoIsSessionUser($_SESSION["userID"]);
    $navBarLinks = $pageService->generateNavLinksForUser($_SESSION["userID"]);
} else {
    $navBarLinks = $pageService->generateNavLinksForUser();
}
$usefulLinks = $pageService->generateUsefulLinks(5);
$featuredLinks = $pageService->generateFeaturedLinks(5);

$currentUser = $dataService->getUserByUserID($_SESSION["userID"]);


if (isset($_POST["gameAccountID"])){
    echo '<pre>';
    print_r($_POST);
    echo '</pre>';
    exit();
}

include("../view/profile.view.php");