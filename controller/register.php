<?php
require_once("../service/page.service.php");
require_once("../service/register.service.php");

if(!empty($_POST)){
    if(empty($_POST['password']) || empty($_POST['retypePassword'])){
        $notification = array(
            'type' => 'danger',
            'title' => 'Error',
            'message' => 'Password can not be empty.'
        );
    } else {
        $registerService = new RegisterService();
        $validationReport = $registerService->validateRegistration($_POST);
        $notification = $registerService->createFeedbackNotification($validationReport);
    }
}


$pageservice = new PAGESERVICE();
$currentPageID = "home";
if (isset($_SESSION["userID"])) {
    $sessionUser = $pageservice->whoIsSessionUser($_SESSION["userID"]);
    $navbarlinks = $pageservice->generateNavLinksForUser($_SESSION["userID"]);
} else {
    $navbarlinks = $pageservice->generateNavLinksForUser();
}

include("../view/register.view.php");