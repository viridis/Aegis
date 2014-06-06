<?php
session_start();
error_reporting(E_ALL);
require_once("../data/links.DAO.php");
require_once("../data/user.DAO.php");

class PAGESERVICE
{
    public function generateUsefulLinks($amount)
    {
        $usefullinksdao = new USEFULLINKSDAO();
        $links = $usefullinksdao->getLinks($amount);
        return $links;
    }

    public function generateFeaturedLinks($amount)
    {
        $featuredlinksdao = new FEATUREDLINKSDAO();
        $links = $featuredlinksdao->getLinks($amount);
        return $links;
    }

    public function generateNavLinksForUser($userId = 0)
    {
        $navbarlinksdao = new NAVBARLINKSDAO();
        $links = $navbarlinksdao->getLinksForUser($userId);
        return $links;
    }

    public function whoIsSessionUser($userId){
        $userdao = new USERDAO();
        $user = $userdao->getUserById($userId);
        return $user;
    }
}