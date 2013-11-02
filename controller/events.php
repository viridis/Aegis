<?php
require_once("../service/page.service.php");
require_once("../service/events.service.php");



$pageservice = new PAGESERVICE();
$currentPageID = "Events";
$navbarlinks = $pageservice->generateNavLinks();
$usefulllinks = $pageservice->generateUsefulLinks(5);
$featuredlinks = $pageservice->generateFeaturedLinks(5);

$eventservice = new eventservice();

$eventlist = $eventservice->listAllEvents();
include("../view/events.view.php");
?>