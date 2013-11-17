<?php
session_start();
require_once("../data/links.DAO.php");

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

    public function generateNavLinks($user = false)
    {
        $navbarlinksdao = new NAVBARLINKSDAO();
        $links = $navbarlinksdao->getLinks($user);
        return $links;
    }
}

?>