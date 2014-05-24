<?php
require_once("../service/page.service.php");
require_once("../service/data.service.php");

$pageService = new PageService();
$currentPageID = "Events";

if ($_SESSION["userID"]) {
    $navBarLinks = $pageService->generateNavLinksForUser($_SESSION["userID"]);
} else {
    $navBarLinks = $pageService->generateNavLinksForUser();
}
$usefulLinks = $pageService->generateUsefulLinks(5);
$featuredLinks = $pageService->generateFeaturedLinks(5);

$dataService = new DataService();
$eventContainer = $dataService->getAllEventInfo();

include("../view/events.view.php");