<?php
require_once("../service/page.service.php");
require_once("../service/data.service.php");
require_once("../service/participate.service.php");

$pageService = new PageService();
$dataService = new DataService();
$participateService = new ParticipateService();

if (!$pageService->authorizedUser(1)) {
    header("location: ./home.php");
}
$currentPageID = "Participate in Events";
if (isset($_SESSION["userID"])) {
    $sessionUser = $pageService->whoIsSessionUser($_SESSION["userID"]);
    $navBarLinks = $pageService->generateNavLinksForUser($_SESSION["userID"]);
} else {
    $navBarLinks = $pageService->generateNavLinksForUser();
}
$usefulLinks = $pageService->generateUsefulLinks(5);
$featuredLinks = $pageService->generateFeaturedLinks(5);

$eventContainer = $participateService->getAllOpenEvents();
$validCharactersForSlotTypes = $participateService->getValidCharactersForSlotClassID();
$currentUser = $dataService->getUserByUserID($_SESSION["userID"]);
include("../view/participate.view.php");