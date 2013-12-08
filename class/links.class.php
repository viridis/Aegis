<?php
class LINKS
{
    private static $idList = array();
    private $id;
    private $name;
    private $location;
    private $visibility;

    function __construct($id, $name, $location)
    {
        $this->id = $id;
        $this->name = $name;
        $this->location = $location;
    }

    public static function create($id, $name, $location)
    {
        self::$idList[$id] = new LINKS($id, $name, $location);
        return self::$idList[$id];
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getWeight()
    {
        return $this->visibility;
    }
}

?>