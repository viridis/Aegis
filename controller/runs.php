<?php
require_once("../service/page.service.php");
require_once("../service/runs.service.php");
require_once("../service/data.service.php");

$pageService = new PageService();
$dataService = new DataService();
$runService = new RunService();

if (!$pageService->authorizedUser(10)) {
    header("location: ./home.php");
}

$currentPageID = "Manage Events";
$allowedToCloseEvent = true;
if (isset($_SESSION["userID"])) {
    $sessionUser = $pageService->whoIsSessionUser($_SESSION["userID"]);
    $navBarLinks = $pageService->generateNavLinksForUser($_SESSION["userID"]);
} else {
    $navBarLinks = $pageService->generateNavLinksForUser();
}
$usefulLinks = $pageService->generateUsefulLinks(5);
$featuredLinks = $pageService->generateFeaturedLinks(5);

$editing = 0;

if (isset($_GET["action"]) && $_GET["action"] == ("openEvent")) {
    if ($runService->openEventFromPostData()) {
        $notification = array(
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Successfully opened eventID: ' . $_POST["eventID"] . '.',
        );
    } else {
        $notification = array(
            'type' => 'danger',
            'title' => 'Error',
            'message' => 'Failed to open eventID: ' . $_POST["eventID"] . '.',
        );
    }
}

if (isset($_GET["action"]) && $_GET["action"] == ("closeEvent")) {
    if ($runService->closeEventFromPostData()) {
        $notification = array(
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Successfully closed eventID: ' . $_POST["eventID"] . '.',
        );
    } else {
        $notification = array(
            'type' => 'danger',
            'title' => 'Error',
            'message' => 'Failed to close eventID: ' . $_POST["eventID"] . '.',
        );
    }
}

if (isset($_GET["editRun"]) && is_numeric($_GET["editRun"]) && isset($_POST["eventTypeID"])) {
    if ($runService->updateEventFromPostData()) {
        $notification = array(
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Successfully updated event: ' . $_POST["eventTypeID"] . '.',
        );
    } else {
        $notification = array(
            'type' => 'danger',
            'title' => 'Error',
            'message' => 'Failed to update event ' . $_POST["eventTypeID"] . '.',
        );
    }
}

if (isset($_GET["editRun"]) && is_numeric($_GET["editRun"])) {
    $editing = 1;
    $eventEditing = $dataService->getEventByEventID($_GET["editRun"]);
}

if (isset($_GET["addRun"]) && $_GET["addRun"] == 1 && isset($_POST["eventTypeID"])) {
    if ($runService->createEventFromPostData()) {
        $notification = array(
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Added event of type: ' . $_POST["eventTypeID"] . '.',
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
$eventTypeContainer = $dataService->getAllEventTypes();

include("../view/runs.view.php");