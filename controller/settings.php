<?php
require_once("../service/page.service.php");
require_once("../service/data.service.php");
require_once("../service/settings.service.php");

$pageService = new PageService();
$dataService = new DataService();
$settingsService = new SettingsService();

$currentPageID = "Settings";
if (isset($_SESSION["userID"])) {
    $sessionUser = $pageService->whoIsSessionUser($_SESSION["userID"]);
    $navBarLinks = $pageService->generateNavLinksForUser($_SESSION["userID"]);
} else {
    header("location: ./home.php");
}

if (isset($_GET["action"]) && $_GET["action"] == "edit")
{
    if ($settingsService->updateUserInfo()){
        $notification = array(
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Successfully updated your details.',
        );
    } else {
        $notification = array(
            'type' => 'danger',
            'title' => 'Error',
            'message' => 'Failed to update details.',
        );
    }
}

if (isset($_GET["action"]) && $_GET["action"] == "password")
{
    if ($settingsService->updateUserPassword()){
        $notification = array(
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Successfully updated your password.',
        );
    } else {
        $notification = array(
            'type' => 'danger',
            'title' => 'Error',
            'message' => 'Failed to update your password.',
        );
    }
}


$user = $dataService->getUserByUserID($_SESSION["userID"]);
include("../view/settings.view.php");