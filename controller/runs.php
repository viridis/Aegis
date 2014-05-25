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

if (isset($_GET["addRun"]) && $_GET["addRun"] == 1 && isset($_POST["eventName"])) {
    if ($runService->validCreateEvent()) {
        $runService->createEventFromPostData();
        $notification = array(
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Added run: '. $_POST["runName"] .'.',
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