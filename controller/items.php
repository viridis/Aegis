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
    try{
        $item = $itemservice->addItem($_POST["addItem"]);
        $notification = array(
            'type' => 'confirmation',
            'message' => 'Successfully added item. ('. $item->getName() .')',
        );
    }catch (Exception $e){
        $notification = array(
            'type' => 'error',
            'message' =>  $e->getMessage(),
        );
    }
}

$itemlist = $itemservice->listAllItems();
if(!empty($_POST["itemAmount"])  && !empty($_POST["itemName"]) && !empty($_POST["itemValue"])){
    $resultItem;
    foreach($itemlist as $item){
        if($_POST["itemName"] == $item->getId()){
            $resultItem = $item;
        }
    }
    if(is_numeric($itemValue = str_replace(',', '', $_POST["itemValue"]))){
        try{
            $runservice->sellDrop($_POST["itemAmount"], $_POST["itemName"], $itemValue);
            $notification = array(
                'type' => 'confirmation',
                'message' => 'Sold: '. $resultItem->getName() .' ('.$_POST["itemAmount"] .') for '. number_format($itemValue) .' ea.',
            );
        }catch (Exception $e) {
            $notification = array(
                'type' => 'error',
                'message' => 'Error Message: '.  $e->getMessage() .' ('. $item->getName() .')',
            );
        }
    }else{
        $notification = array(
            'type' => 'error',
            'message' => 'Error Message: '. $_POST["itemValue"] .' is not a valid number.',
        );
    }
}elseif(isset($_POST['itemAmount']) && (empty($_POST["itemAmount"]) || empty($_POST["itemName"]) || empty($_POST["itemValue"]))){
    $notification = array(
        'type' => 'error',
        'message' => 'Error Message: One of your inputfields was filled in.',
    );
}

include("../view/items.view.php");
?>