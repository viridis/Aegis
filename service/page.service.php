<?php
session_start();
require_once("../data/links.DAO.php");

class PAGESERVICE{	
	public function generateUsefullLinks($amount){
		$usefulllinksdao = new USEFULLLINKSDAO();
		$links = $usefulllinksdao->getLinks($amount);
		return $links;
	}

	public function generateFeaturedLinks($amount){
		$featuredlinksdao = new FEATUREDLINKSDAO();
		$links = $featuredlinksdao->getLinks($amount);
		return $links;
	}

	public function generateNavLinks(){
		$navbarlinksdao = new NAVBARLINKSDAO();
		$links = $navbarlinksdao->getLinks();
		return $links;
	}
}
?>