<?php
require_once("../service/page.service.php");
require_once("../service/register.service.php");

if (!empty($_POST)) {
    if (empty($_POST['password']) || empty($_POST['retypePassword'])) {
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


$pageService = new PAGESERVICE();
$currentPageID = "home";
if (isset($_SESSION["userID"])) {
    $sessionUser = $pageService->whoIsSessionUser($_SESSION["userID"]);
    $navBarLinks = $pageService->generateNavLinksForUser($_SESSION["userID"]);
} else {
    $navBarLinks = $pageService->generateNavLinksForUser();
}

include("../view/register.view.php");