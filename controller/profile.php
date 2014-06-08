<?php
require_once("../service/page.service.php");
require_once("../service/data.service.php");
require_once("../service/profile.service.php");

$pageService = new PageService();
$dataService = new DataService();
$profileService = new ProfileService();

if (!$pageService->authorizedUser(1)) {
    header("location: ./home.php");
}

$currentPageID = "Profile";
if (isset($_SESSION["userID"])) {
    $sessionUser = $pageService->whoIsSessionUser($_SESSION["userID"]);
    $navBarLinks = $pageService->generateNavLinksForUser($_SESSION["userID"]);
} else {
    $navBarLinks = $pageService->generateNavLinksForUser();
}
$usefulLinks = $pageService->generateUsefulLinks(5);
$featuredLinks = $pageService->generateFeaturedLinks(5);

if (isset($_GET["addGameAccount"]) && isset($_POST["accountName"])) {
    if ($profileService->addGameAccountToUser()) {
        $notification = array(
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Successfully created game account.',
        );
    } else {
        $notification = array(
            'type' => 'danger',
            'title' => 'Error',
            'message' => 'Failed to create game account.',
        );
    }
}

if (isset($_GET["addCharacter"]) && is_numeric($_POST["accountID"])) {
    if ($profileService->addCharacterToGameAccount()) {
        $notification = array(
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Successfully created character.',
        );
    } else {
        $notification = array(
            'type' => 'danger',
            'title' => 'Error',
            'message' => 'Failed to create character.',
        );
    }
}


if (isset($_POST["gameAccountID"])) {
    $profileService->getJSONGameAccount();
    exit();
}

$currentUser = $dataService->getUserByUserID($_SESSION["userID"]);
$charClassContainer = $dataService->getAllCharClasses();

include("../view/profile.view.php");