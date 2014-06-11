<?php
require_once("../service/page.service.php");
require_once("../service/data.service.php");
require_once("../service/participate.service.php");

$pageService = new PageService();
$dataService = new DataService();
$participateService = new ParticipateService();

if (!$pageService->authorizedUser(1)) {
    header("location: ./home.php");
}
$currentPageID = "Participate in Events";
if (isset($_SESSION["userID"])) {
    $sessionUser = $pageService->whoIsSessionUser($_SESSION["userID"]);
    $navBarLinks = $pageService->generateNavLinksForUser($_SESSION["userID"]);
} else {
    $navBarLinks = $pageService->generateNavLinksForUser();
}
$usefulLinks = $pageService->generateUsefulLinks(5);
$featuredLinks = $pageService->generateFeaturedLinks(5);

if (isset($_GET["updateSlot"]) && isset($_POST["join_slot_" . $_GET["updateSlot"]]))
{
    if ($participateService->setSlotTaken()){
        $notification = array(
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Successfully joined event.',
        );
    } else {
        $notification = array(
            'type' => 'danger',
            'title' => 'Error',
            'message' => 'Failed to join event.',
        );
    }
}

if (isset($_GET["updateSlot"]) && isset($_POST["change_slot_" . $_GET["updateSlot"]]))
{
    if ($participateService->updateCharacterInSlot()){
        $notification = array(
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Successfully updated character in slot.',
        );
    } else {
        $notification = array(
            'type' => 'danger',
            'title' => 'Error',
            'message' => 'Failed to update character in slot.',
        );
    }
}

if (isset($_GET["updateSlot"]) && isset($_POST["vacate_slot_" . $_GET["updateSlot"]]))
{
    if ($participateService->vacateCharacterFromSlot()){
        $notification = array(
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Successfully vacated slot.',
        );
    } else {
        $notification = array(
            'type' => 'danger',
            'title' => 'Error',
            'message' => 'Failed to vacate slot.',
        );
    }
}


$eventContainer = $participateService->getAllOpenEvents();
$validCharactersForSlotTypes = $participateService->getValidCharactersForSlotClassID();
$isAdmin = false;
/** @var User $currentUser */
$currentUser = $dataService->getUserByUserID($_SESSION["userID"]);
if (isset($_GET["admin"]) && $currentUser->getRoleLevel() == 10){
    $isAdmin = true;
    $validCharactersForSlotTypes = $participateService->getAllValidCharactersForSlotClassID();
}
include("../view/participate.view.php");