<?php
require_once("../service/page.service.php");
require_once("../service/manageSlots.service.php");
require_once("../service/data.service.php");

$pageService = new PageService();
$manageSlotsService = new ManageSlotsService();

if (!$pageService->authorizedUser(10)) {
    header("location: ./home.php");
}

$currentPageID = "Manage Slots";
if (isset($_SESSION["userID"])) {
    $sessionUser = $pageService->whoIsSessionUser($_SESSION["userID"]);
    $navBarLinks = $pageService->generateNavLinksForUser($_SESSION["userID"]);
} else {
    $navBarLinks = $pageService->generateNavLinksForUser();
}
$usefulLinks = $pageService->generateUsefulLinks(5);
$featuredLinks = $pageService->generateFeaturedLinks(5);

include("../view/manageSlots.view.php");
