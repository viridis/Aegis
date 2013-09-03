<?php
class PAYOUT{
	private static $idList = array();
	private $id;
	private $event; //event object
	private $droppedItem; //item object
	private $user; //user object
	private $paymentState; //
	
	function __construct($id, $event, $droppedItem, $user, $paymentState){
		$this->id = $id;
		$this->event = $event;
		$this->droppedItem = $droppedItem;
		$this->user = $user;
		$this->paymentState = $paymentState;
	}
	
	public static function create($id, $event, $droppedItem, $user, $paymentState){
		if (!isset(self::$idList[$id])) {
			self::$idList[$id] = new PAYOUT($id, $event, $droppedItem, $user, $paymentState);
		}
		return self::$idList[$id];
	}
	
	public function getId(){
		return $this->id;
	}
	public function getEvent(){
		return $this->event;
	}
	public function getDroppedItem(){
		return $this->droppedItem;
	}
	public function getUser(){
		return $this->user;
	}
	public function getPaymentState(){
		return $this->paymentState;
	}
}

?>