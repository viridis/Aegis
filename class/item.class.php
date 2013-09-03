<?php
class ITEM{
	private static $idList = array();
	private $id;
	private $name;
	private $talonID;

	
	function __construct($id, $name, $talonID){
		$this->id = $id;
		$this->name = $name;
		$this->talonID = $talonID;
	}
	
	public static function create($id, $name, $talonID){
		if (!isset(self::$idList[$id])) {
			self::$idList[$id] = new ITEM($id, $name, $talonID);
		}
		return self::$idList[$id];
	}
	
	public function getId(){
		return $this->id;
	}
	public function getName(){
		return $this->name;
	}
	public function getTalonID(){
		return $this->talonID;
	}
	
	public function setId($id){
		$this->id = $id;
	}
	public function setName($name){
		$this->name = $name;
	}
	public function setTalonID($talonID){
		$this->talonID = $talonID;
	}
}

?>