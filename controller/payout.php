<?php
require_once("../service/page.service.php");
require_once("../service/payout.service.php");
require_once("../service/events.service.php");



$pageservice = new PAGESERVICE();
$currentPageID = "Pay Out";
$navbarlinks = $pageservice->generateNavLinks();
$usefulllinks = $pageservice->generateUsefulLinks(5);
$featuredlinks = $pageservice->generateFeaturedLinks(5);

$eventservice = new eventservice();
$payoutservice = new payoutservice();

if (isset ($_GET["payout"]) &&  is_numeric($_GET["payout"])) {
	$eventsByUserID = $eventservice->getEventsByParticipantID($_GET["payout"]);
	$payoutservice->payOutUserID($_GET["payout"], $eventsByUserID);
	header("location: ./payout.php");
}

$eventlist = $eventservice->listAllEvents();
$payoutList = $payoutservice->listAllPayouts($eventlist);

include("../view/payout.view.php");

?>