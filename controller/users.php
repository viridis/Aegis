<?php
require_once("../service/page.service.php");
require_once("../service/users.service.php");



$pageservice = new PAGESERVICE();
$currentPageID = "Manage Users";
$navbarlinks = $pageservice->generateNavLinks();
$usefulllinks = $pageservice->generateUsefulLinks(5);
$featuredlinks = $pageservice->generateFeaturedLinks(5);

$userservice = new userservice();
if(isset($_POST["addUser"])){
	$userservice->addUser($_POST["addUser"]);
}

$userlist = $userservice->listAllUsers();
include("../view/users.view.php");
?>