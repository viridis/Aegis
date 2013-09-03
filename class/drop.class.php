<?php
class DROP{
	private static $idList = array();
	private $dropID;
	private $name;
	private $talonID;
	private $dropValue;
	
	function __construct($dropID, $name, $talonID, $dropValue){
		$this->dropID = $dropID;
		$this->name = $name;
		$this->talonID = $talonID;
		$this->dropValue = $dropValue;
	}
	
	public static function create($dropID, $name, $talonID, $dropValue){
		if (!isset(self::$idList[$dropID])) {
			self::$idList[$dropID] = new DROP($dropID, $name, $talonID, $dropValue);
		}
		return self::$idList[$dropID];
	}
	
	public function getDropId(){
		return $this->dropID;
	}
	public function getName(){
		return $this->name;
	}
	public function getTalonID(){
		return $this->talonID;
	}
	public function getDropValue(){
		return $this->dropValue;
	}
	
	
	public function setDropId($id){
		$this->dropID = $id;
	}
	public function setName($name){
		$this->name = $name;
	}
	public function setTalonID($talonID){
		$this->talonID = $talonID;
	}
	public function setDropValue($value){
		$this->dropValue = $value;
	}
}

?>