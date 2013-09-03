<?php
require_once("../service/page.service.php");



$pageservice = new PAGESERVICE();
$currentPageID = "home";
$navbarlinks = $pageservice->generateNavLinks();
$usefulllinks = $pageservice->generateUsefullLinks(5);
$featuredlinks = $pageservice->generateFeaturedLinks(5);
include("../view/home.view.php");
?>