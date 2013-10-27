<?php
require_once("../service/page.service.php");
require_once("../service/items.service.php");
require_once("../service/runs.service.php");



$pageservice = new PAGESERVICE();
$currentPageID = "Manage Items";
$navbarlinks = $pageservice->generateNavLinks();
$usefulllinks = $pageservice->generateUsefulLinks(5);
$featuredlinks = $pageservice->generateFeaturedLinks(5);

$itemservice = new itemservice();
$runservice = new runservice();
if(isset($_POST["addItem"])){
	$itemservice->addItem($_POST["addItem"]);
}

$itemlist = $itemservice->listAllItems();
if(isset($_POST["itemAmount"])  && isset($_POST["itemName"]) && isset($_POST["itemValue"])){
    try{
        $runservice->sellDrop($_POST["itemAmount"], $_POST["itemName"], $_POST["itemValue"]);
    }catch (Exception $e) {
        foreach($itemlist as $item){
            if($_POST["itemName"] == $item->getId()){
                $errorMessage = 'Error Message: '.  $e->getMessage() .' ('. $item->getName() .')';
            }
        }
    }
}

include("../view/items.view.php");
?>