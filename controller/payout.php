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
    if($sessionUser->getRoleLevel() >= 10){
        $allowedToPayOut = true;
    }
} else {
    $navbarlinks = $pageservice->generateNavLinksForUser();
}
$usefulllinks = $pageservice->generateUsefulLinks(5);
$featuredlinks = $pageservice->generateFeaturedLinks(5);

$eventservice = new eventservice();
$payoutservice = new payoutservice();
$allowedToPayOut = true;

if (isset ($_GET["action"]) && $_GET["action"] == 'payout' && $allowedToPayOut) {
    $userservice = new userservice();
    $user = $userservice->getUserByID($_POST["userId"]);

    try {
        userservice::payoutByUserID($_POST["userId"]);
        header("location: ./payout.php?paidOut=" . $user->getUserLogin());
    } catch (Exception $e) {
        $notification = array(
            'type' => 'danger',
            'title' => 'Error',
            'message' => 'Could not pay out ' . $user->getUserLogin() . '<br />' . $e->getMessage(),
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
$userlist = userservice::listAllUsers();

include("../view/payout.view.php");

?>