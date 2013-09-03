<?php
require_once("../data/run.DAO.php");

class runservice{
	public function listAllEvents(){
		$rundao = new RUNDAO();
		$eventlist = $rundao->getAllEvents();
		return $eventlist;
	}
	
	public function addRun($name, $date){
		$rundao = new RUNDAO();
		$run = $rundao->addRun($name, $date);
		return $run;
	}
}
?>