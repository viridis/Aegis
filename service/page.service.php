<?php
session_start();
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
require_once("../data/links.DAO.php");
require_once("../service/data.service.php");

class PageService
{
    public function generateUsefulLinks($amount)
    {
        $usefulLinksDAO = new UsefulLinksDAO();
        $links = $usefulLinksDAO->getLinks($amount);
        return $links;
    }

    public function generateFeaturedLinks($amount)
    {
        $featuredLinksDAO = new FeaturedLinksDAO();
        $links = $featuredLinksDAO->getLinks($amount);
        return $links;
    }

    public function generateNavLinksForUser($userId = 0)
    {
        $navBarLinksDAO = new NavBarLinksDAO();
        $links = $navBarLinksDAO->getLinksForUser($userId);
        return $links;
    }

    public function whoIsSessionUser($userID)
    {
        $dataService = new DataService();
        $user = $dataService->getUserByUserID($userID);
        return $user;
    }

    public function authorizedUser($userID, $requiredRoleLevel)
    {
        $dataService = new DataService();
        /** @var User $user */
        $user = $dataService->getUserByUserID($userID);
        if ($user->getRoleLevel() < $requiredRoleLevel) {
            return false;
        }
        return true;
    }
}