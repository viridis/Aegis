<?php
class USER{
	private static $idList = array();
	private $id;
	private $name;
	private $mailname;
	private $password;
	
	function __construct($id, $name, $mailname, $password){
		$this->id = $id;
		$this->name = $name;
		$this->mailname = $mailname;
		$this->password = $password;
	}
	
	public static function create($id, $name, $mailname, $password){
		if (!isset(self::$idList[$id])) {
			self::$idList[$id] = new USER($id, $name, $mailname, $password);
		}
		return self::$idList[$id];
	}
	
	public function getId(){
		return $this->id;
	}
	public function getName(){
		return $this->name;
	}
	public function getMailName(){
		return $this->mailname;
	}
	public function getPassword(){
		return $this->password;
	}
	
	public function setName($name){
		$this->name = $name;
	}
	public function setMailName($mailname){
		$this->mailname = $mailname;
	}
	public function setPassword($password){
		$this->password = $password;
	}
}
?>