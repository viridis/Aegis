<?php
require_once("../service/page.service.php");
require_once("../service/payout.service.php");
require_once("../service/events.service.php");
require_once("../service/users.service.php");


$pageservice = new PAGESERVICE();
$currentPageID = "Pay Out";
if ($_SESSION["userID"]) {
    $navbarlinks = $pageservice->generateNavLinks();
} else {
    $navbarlinks = $pageservice->generateNavLinks('user');
}
$usefulllinks = $pageservice->generateUsefulLinks(5);
$featuredlinks = $pageservice->generateFeaturedLinks(5);

$eventservice = new eventservice();
$payoutservice = new payoutservice();

if (isset ($_GET["action"]) && $_GET["action"] == 'payout' && $_SESSION["userID"] && is_numeric($_POST["userId"])) {
    $userservice = new userservice();
    $user = $userservice->getUserByID($_POST["userId"]);
    try {
        $eventsByUserID = $eventservice->getEventsByParticipantID($_POST["userId"]);
        $payoutservice->payOutUserID($_POST["userId"], $eventsByUserID);
        header("location: ./payout.php?paidOut=" . $user->getName());
    } catch (Exception $e) {
        $notification = array(
            'type' => 'error',
            'message' => 'Could not pay out ' . $user->getName() . '<br />' . $e->getMessage(),
        );
    }
}
if (isset($_GET["paidOut"])) {
    $notification = array(
        'type' => 'confirmation',
        'message' => 'Paid out ' . $_GET['paidOut'] . ' successfully.',
    );
}

$eventlist = $eventservice->listAllEvents();
$payoutList = $payoutservice->listAllPayouts($eventlist);

include("../view/payout.view.php");

?>