<?php
require_once("../service/page.service.php");
require_once("../service/data.service.php");
require_once("../service/payout.service.php");

$pageService = new PageService();
$payoutService = new PayoutService();
$dataService= new DataService();

$currentPageID = "Pay Out";

if (isset($_SESSION["userID"])) {
    /** @var User $sessionUser */
    $sessionUser = $pageService->whoIsSessionUser($_SESSION["userID"]);
    $navBarLinks = $pageService->generateNavLinksForUser($_SESSION["userID"]);
    if($sessionUser->getRoleLevel() >= 10){
        $allowedToPayOut = true;
    }
} else {
    $navBarLinks = $pageService->generateNavLinksForUser();
}
$usefulLinks = $pageService->generateUsefulLinks(5);
$featuredLinks = $pageService->generateFeaturedLinks(5);

if (isset ($_GET["action"]) && $_GET["action"] == 'payout' && $allowedToPayOut) {
    /** @var User $user */
    $user = $dataService->getUserByUserID($_POST["userId"]);
    try {
        $payoutService->payoutUser($user);
        header("location: ./payout.php?paidOut=" . $user->getUserLogin());
    } catch (Exception $e) {
        $notification = array(
            'type' => 'danger',
            'title' => 'Error',
            'message' => 'Could not pay out ' . $user->getUserLogin() . '<br />' . $e->getMessage(),
        );
    }
}

if (isset($_GET["paidOut"])) {
    $notification = array(
        'type' => 'success',
        'title' => 'Confirmation',
        'message' => 'Paid out ' . $_GET['paidOut'] . ' successfully.',
    );
}

$userContainer = $dataService->getAllUserInfo();
include("../view/payout.view.php");