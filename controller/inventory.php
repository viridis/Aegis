<?php
require_once("../service/page.service.php");
require_once("../service/inventory.service.php");
require_once("../service/data.service.php");
$pageService = new PageService();
$dataService = new DataService();
$inventoryService = new InventoryService();

if (!$pageService->authorizedUser(10)) {
    header("location: ./home.php");
}

$currentPageID = "Inventory";
if (isset($_SESSION["userID"])) {
    $sessionUser = $pageService->whoIsSessionUser($_SESSION["userID"]);
    $navBarLinks = $pageService->generateNavLinksForUser($_SESSION["userID"]);
} else {
    $navBarLinks = $pageService->generateNavLinksForUser();
}
$usefulLinks = $pageService->generateUsefulLinks(5);
$featuredLinks = $pageService->generateFeaturedLinks(5);

/** @var User $currentUser */
$currentUser = $dataService->getUserByUserID($_SESSION["userID"]);
$canEditInventory = false;
if ($currentUser->getRoleLevel() == 10)
{
    $canEditInventory = true;
}
$collatedDrops = $inventoryService->getAllCollatedDrops();
include("../view/inventory.view.php");
