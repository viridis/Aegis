<?php
require_once("../service/page.service.php");
require_once("../service/runs.service.php");
require_once("../service/data.service.php");

if (!$_SESSION["userID"]) {
    header("location: ./home.php");
}

$pageService = new PageService();
$dataService = new DataService();
$currentPageID = "Manage Events";
if (isset($_SESSION["userID"])) {
    $sessionUser = $pageService->whoIsSessionUser($_SESSION["userID"]);
    $navBarLinks = $pageService->generateNavLinksForUser($_SESSION["userID"]);
} else {
    $navBarLinks = $pageService->generateNavLinksForUser();
}
$usefulLinks = $pageService->generateUsefulLinks(5);
$featuredLinks = $pageService->generateFeaturedLinks(5);

$runService = new RunService();
$editing = 0;

if (isset($_GET["editRun"]) && is_numeric($_GET["editRun"]) && isset($_POST["eventName"])) {
    if ($runService->updateEventFromPostData()){
        $notification = array(
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Successfully updated event: ' . $_POST["eventID"] . '.',
        );
    } else {
        $notification = array(
            'type' => 'danger',
            'title' => 'Error',
            'message' => 'Failed to update event ' . $_POST["eventID"] . '.',
        );
    }
}

if (isset($_GET["editRun"]) && is_numeric($_GET["editRun"])) {
    $editing = 1;
    $eventEditing = $dataService->getEventByEventID($_GET["editRun"]);
}

if (isset($_GET["addRun"]) && $_GET["addRun"] == 1 && isset($_POST["eventName"])) {
    if ($runService->createEventFromPostData()) {
        $notification = array(
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Added event: ' . $_POST["eventName"] . '.',
        );
    } else {
        $notification = array(
            'type' => 'danger',
            'title' => 'Error',
            'message' => 'Event details not filled in correctly',
        );
    }

}

$eventContainer = $dataService->getAllEventInfo();

include("../view/runs.view.php");