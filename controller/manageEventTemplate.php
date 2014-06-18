<?php
require_once("../service/page.service.php");
require_once("../service/manageEventTemplate.service.php");
require_once("../service/data.service.php");

$pageService = new PageService();
$manageEventTemplateService = new ManageEventTemplateService();
$dataService = new DataService();

if (!$pageService->authorizedUser(10)) {
    header("location: ./home.php");
}

$currentPageID = "Event Templates";
if (isset($_SESSION["userID"])) {
    $sessionUser = $pageService->whoIsSessionUser($_SESSION["userID"]);
    $navBarLinks = $pageService->generateNavLinksForUser($_SESSION["userID"]);
} else {
    $navBarLinks = $pageService->generateNavLinksForUser();
}
$usefulLinks = $pageService->generateUsefulLinks(5);
$featuredLinks = $pageService->generateFeaturedLinks(5);
$eventTypeArray = $dataService->getAllEventTypes();

if (isset($_POST["id"]) && isset($_POST["value"])) {
    $manageEventTemplateService->updateEventTypeFromAJAX();
    exit();
}

if (isset($_POST["newEventTypeName"])) {
    $manageEventTemplateService->createNewEventTypeFromAJAX();
    exit();
}
include("../view/manageEventTemplate.view.php");