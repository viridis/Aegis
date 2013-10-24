<?php
require_once("../service/page.service.php");
require_once("../service/runs.service.php");
require_once("../service/items.service.php");
require_once("../service/users.service.php");




$pageservice = new PAGESERVICE();
$currentPageID = "Manage Runs";
$navbarlinks = $pageservice->generateNavLinks();
$usefulllinks = $pageservice->generateUsefulLinks(5);
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
if(isset($_GET["editrun"]) && is_numeric($_GET["editrun"]) && isset($_GET["add"]) && $_GET["add"] == 'users'){
    $run = $runservice->addParticipantToRun($_GET["editrun"], $_GET["id"]);
    $result["action"] = 'added';
    $result["database"] = 'users';
    $result["id"] = $_GET["id"];
    print(json_encode($result));
    exit();
}
if(isset($_GET["editrun"]) && is_numeric($_GET["editrun"]) && isset($_GET["add"]) && $_GET["add"] == 'items'){
    $run = $runservice->addItemToRun($_GET["editrun"], $_GET["id"]);
    $result["action"] = 'added';
    $result["database"] = 'items';
    $result["id"] = $run;
    print(json_encode($result));
    exit();
}if(isset($_GET["editrun"]) && is_numeric($_GET["editrun"]) && isset($_GET["delete"]) && $_GET["delete"] == 'users'){
    $run = $runservice->removeParticipantFromRun($_GET["editrun"], $_GET["id"]);
    $result["action"] = 'deleted';
    $result["database"] = 'users';
    $result["id"] = $run;
    print(json_encode($result));
    exit();
}
if(isset($_GET["editrun"]) && is_numeric($_GET["editrun"]) && isset($_GET["delete"]) && $_GET["delete"] == 'items'){
    $run = $runservice->removeItemFromRun($_GET["editrun"], $_GET["id"]);
    $result["action"] = 'deleted';
    $result["database"] = 'items';
    $result["id"] = $run;
    print(json_encode($result));
    exit();
}
$editing = 0;
if(isset($_GET["editrun"]) && is_numeric($_GET["editrun"])){
	$run = $runservice->getRunById($_GET["editrun"]);
	$itemservice = new itemservice();
	$itemList = $itemservice->listAllItems();
	$userservice = new userservice();
	$userList = $userservice->listAllUsers();
	$editing = 1;
}

$eventlist = $runservice->listAllEvents();

include("../view/runs.view.php");
?>