<?php
require_once("../service/page.service.php");



$pageservice = new PAGESERVICE();
$currentPageID = "home";
if ($_SESSION["userID"]) {
    $navbarlinks = $pageservice->generateNavLinks();
} else {
    $navbarlinks = $pageservice->generateNavLinks('user');
}
$usefulllinks = $pageservice->generateUsefulLinks(5);
$featuredlinks = $pageservice->generateFeaturedLinks(5);

if (isset($_GET['action']) && $_GET['action'] == 'loggedOut') {
    $notification = array(
        'type' => 'confirmation',
        'message' => 'You have successfully logged out.',
    );
} elseif (isset($_GET['action']) && $_GET['action'] == 'loggedIn') {
    $notification = array(
        'type' => 'confirmation',
        'message' => 'You have successfully logged in.',
    );
}
include("../view/home.view.php");
?>