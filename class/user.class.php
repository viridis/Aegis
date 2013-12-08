<?php
class USER{
	private static $idList = array();
	private $id;
	private $name;
    private $email;
	private $mailname;
    private $password;
    private $permission;
    private $forumname;
	
	function __construct($id, $name, $mailname, $permission){
		$this->id = $id;
        $this->name = $name;
		$this->mailname = $mailname;
        $this->permission = $permission;
	}
	
	public static function create($id, $name, $mailname, $permission){
		if (!isset(self::$idList[$id])) {
			self::$idList[$id] = new USER($id, $name, $mailname, $permission);
		}
		return self::$idList[$id];
	}

    public function clearUser(){
        self::$idList = array();
    }
	
	public function getId(){
		return $this->id;
	}
	public function getName(){
		return $this->name;
	}
    public function getEmail(){
        return $this->email;
    }
	public function getMailName(){
		return $this->mailname;
	}
	public function getPassword(){
		return $this->password;
	}
    public function getPermission(){
        return $this->permission;
    }
    public function getForumName(){
        return $this->forumname;
    }

    public function setName($name){
        $this->name = $name;
    }
    public function setEmail($email){
        $this->email = $email;
    }
	public function setMailName($mailname){
		$this->mailname = $mailname;
	}
    public function setPassword($password){
        $this->password = $password;
    }
    public function setPermission($permission){
        $this->permission = $permission;
    }
    public function setForumName($setForumName){
        $this->forumname = $setForumName;
    }
}

?>