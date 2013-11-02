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
    try{
        $user = $userservice->addUser($_POST["addUser"]);
        $notification = array(
            'type' => 'confirmation',
            'message' => 'Successfully added user. ('. $user->getName() .')',
        );
    }catch (Exception $e){
        $notification = array(
            'type' => 'error',
            'message' =>  $e->getMessage(),
        );
    }
}

$userlist = $userservice->listAllUsers();
include("../view/users.view.php");
?>