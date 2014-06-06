<?php
require_once("../service/page.service.php");
require_once("../service/payout.service.php");
require_once("../service/events.service.php");
require_once("../service/users.service.php");


$pageservice = new PAGESERVICE();
$currentPageID = "Pay Out";
if (isset($_SESSION["userID"])) {
    $sessionUser = $pageservice->whoIsSessionUser($_SESSION["userID"]);
    $navbarlinks = $pageservice->generateNavLinksForUser($_SESSION["userID"]);
    if($sessionUser->getPermission() >= 10){
        $allowedToPayOut = true;
    }
} else {
    $navbarlinks = $pageservice->generateNavLinksForUser();
}

$eventservice = new eventservice();
$payoutservice = new payoutservice();

if (isset ($_GET["action"]) && $_GET["action"] == 'payout' && $allowedToPayOut) {
    $userservice = new userservice();
    $user = $userservice->getUserByID($_POST["userId"]);
    try {
        $eventsByUserID = $eventservice->getEventsByParticipantID($_POST["userId"]);
        $payoutservice->payOutUserID($_POST["userId"], $eventsByUserID);
        header("location: ./payout.php?paidOut=" . $user->getName());
    } catch (Exception $e) {
        $notification = array(
            'type' => 'danger',
            'title' => 'Error',
            'message' => 'Could not pay out ' . $user->getName() . '<br />' . $e->getMessage(),
        );
    }
}
if (isset($_GET["paidOut"])) {
    $notification = array(
        'type' => 'success',
        'title' => 'Confirmation',
        'message' => 'Paid out ' . $_GET['paidOut'] . ' successfully.',
    );
}

$eventlist = $eventservice->listAllEvents();
$payoutList = $payoutservice->listAllPayouts($eventlist);

include("../view/payout.view.php");