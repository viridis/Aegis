<?php
require_once("../data/payout.DAO.php");
require_once("../data/event.DAO.php");

class payoutservice{
	public function listAllPayouts($eventList){
		$payoutdao = new PAYOUTDAO();
		$userlist = $payoutdao->getAllPayoutsFromEvents($eventList);
		return $userlist;
	}
	
	public function payOutUserID($id, $eventList){
		$payoutdao = new PAYOUTDAO();
		$payout = $payoutdao->payOutUser($id, $eventList);
		return $payout;
	}
}
?>