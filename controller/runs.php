<?php
require_once("../service/page.service.php");
require_once("../service/runs.service.php");



$pageservice = new PAGESERVICE();
$currentPageID = "Manage Runs";
$navbarlinks = $pageservice->generateNavLinks();
$usefulllinks = $pageservice->generateUsefullLinks(5);
$featuredlinks = $pageservice->generateFeaturedLinks(5);

$runservice = new runservice();

if(isset($_GET["addRun"]) && $_GET["addRun"] == 1 && isset($_POST["runName"]) && isset($_POST["runDate"])){
	if($_POST["runName"] == "" || $_POST["runDate"] == ""){
		//print('Fill in Name and Time');
	}
	else{
		$runservice->addRun($_POST["runName"], $_POST["runDate"]);
	}
	
}

$eventlist = $runservice->listAllEvents();

include("../view/runs.view.php");
?>