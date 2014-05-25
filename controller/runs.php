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

if (isset($_GET["addRun"]) && $_GET["addRun"] == 1 && isset($_POST["runName"]) && isset($_POST["runDate"])) {
    if ($_POST["runName"] == "" || $_POST["runDate"] == "") {
        $notification = array(
            'type' => 'danger',
            'title' => 'Error',
            'message' => 'Name and Date should be filled in.',
        );
    } else {
        $runService->addRun($_POST["runName"], $_POST["runDate"]);
        $notification = array(
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Added run: '. $_POST["runName"] .'.',
        );
    }

}
if (isset($_GET["editrun"]) && is_numeric($_GET["editrun"]) && isset($_GET["add"]) && $_GET["add"] == 'users') {
    $event = $runService->addParticipantToRun($_GET["editrun"], $_GET["id"]);
    $result["action"] = 'added';
    $result["database"] = 'users';
    $result["id"] = $_GET["id"];
    print(json_encode($result));
    exit();
}
if (isset($_GET["editrun"]) && is_numeric($_GET["editrun"]) && isset($_GET["add"]) && $_GET["add"] == 'items') {
    $runID = $runService->addItemToRun($_GET["editrun"], $_GET["id"]);
    $result["action"] = 'added';
    $result["database"] = 'items';
    $result["id"] = $runID;
    print(json_encode($result));
    exit();
}
if (isset($_GET["editrun"]) && is_numeric($_GET["editrun"]) && isset($_GET["delete"]) && $_GET["delete"] == 'users') {
    $event = $runService->removeParticipantFromRun($_GET["editrun"], $_GET["id"]);
    $result["action"] = 'deleted';
    $result["database"] = 'users';
    $result["id"] = $event;
    print(json_encode($result));
    exit();
}
if (isset($_GET["editrun"]) && is_numeric($_GET["editrun"]) && isset($_GET["delete"]) && $_GET["delete"] == 'items') {
    $event = $runService->removeItemFromRun($_GET["editrun"], $_GET["id"]);
    $result["action"] = 'deleted';
    $result["database"] = 'items';
    $result["id"] = $event;
    print(json_encode($result));
    exit();
}
$editing = 0;
if (isset($_GET["editrun"]) && is_numeric($_GET["editrun"])) {
    /** @var Event $event */
    $event = $dataService->getEventByEventID($_GET["editrun"]);
    $slotList = $event->getSlotList();
    $itemList = $dataService->listAllItems();
    $itemListCount = ceil(count($itemList) / 3);
    $itemList = array(
        array_slice($itemList, 0, $itemListCount),
        array_slice($itemList, $itemListCount, $itemListCount),
        array_slice($itemList, $itemListCount * 2, $itemListCount),

    );
    $userContainer = $dataService->getAllUserInfo();
    $userContainerCount = ceil(count($userContainer) / 3);
    $userContainer = array(
        array_slice($userContainer, 0, $userContainerCount),
        array_slice($userContainer, $userContainerCount, $userContainerCount),
        array_slice($userContainer, $userContainerCount * 2, $userContainerCount),

    );
    $editing = 1;
}

$eventContainer = $dataService->getAllEventInfo();

include("../view/runs.view.php");