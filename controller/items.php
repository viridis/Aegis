<?php
require_once("../service/page.service.php");
require_once("../service/items.service.php");



$pageservice = new PAGESERVICE();
$currentPageID = "Manage Items";
$navbarlinks = $pageservice->generateNavLinks();
$usefulllinks = $pageservice->generateUsefulLinks(5);
$featuredlinks = $pageservice->generateFeaturedLinks(5);

$itemservice = new itemservice();
if(isset($_POST["addItem"])){
	$itemservice->addItem($_POST["addItem"]);
}

$itemlist = $itemservice->listAllItems();
include("../view/items.view.php");
?>