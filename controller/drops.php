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
$eventDropCollation = $dropsPageService->collateDropsForEvents($eventContainer);
/** @var User $currentUser */
$currentUser = $dataService->getUserByUserID($_SESSION["userID"]);
$itemContainer = $dataService->getAllItems();
if (isset($_POST["addDrop"]) && isset($_POST["eventID"])) {
    $dropsPageService->addDropToEventAJAX();
    exit();
}

if (isset($_POST["removeDrop"]) && isset($_POST["eventID"])) {
    $dropsPageService->removeDropFromEventAJAX();
    exit();
}

include("../view/drops.view.php");
