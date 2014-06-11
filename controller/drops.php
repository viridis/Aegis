<?php
require_once("../service/page.service.php");
require_once("../service/dropsPage.service.php");
require_once("../service/data.service.php");

$pageService = new PageService();
$dataService = new DataService();
$dropsPageService = new DropsPageService();

if (!$pageService->authorizedUser(10)) {
    header("location: ./home.php");
}

$currentPageID = "Manage Drops";
if (isset($_SESSION["userID"])) {
    $sessionUser = $pageService->whoIsSessionUser($_SESSION["userID"]);
    $navBarLinks = $pageService->generateNavLinksForUser($_SESSION["userID"]);
} else {
    $navBarLinks = $pageService->generateNavLinksForUser();
}
$usefulLinks = $pageService->generateUsefulLinks(5);
$featuredLinks = $pageService->generateFeaturedLinks(5);

$eventContainer = $dropsPageService->getAllClosedEvents();
/** @var User $currentUser */
$currentUser = $dataService->getUserByUserID($_SESSION["userID"]);
include("../view/drops.view.php");
