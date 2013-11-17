<?php
class LINKS
{
    private static $idList = array();
    private $id;
    private $name;
    private $location;
    private $weight;

    function __construct($id, $name, $location, $weight)
    {
        $this->id = $id;
        $this->name = $name;
        $this->location = $location;
        $this->weight = $weight;
    }

    public static function create($id, $name, $location, $weight)
    {
        self::$idList[$id] = new LINKS($id, $name, $location, $weight);
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
        return $this->weight;
    }
}

?>