<?php
class EVENT
{
    private static $idList = array();
    private $id;
    private $name;
    private $time;
    private $description;
    private $participants = array();
    private $drops = array();
    private $totalValue;
    private $totalParticipants;

    function __construct($id, $name, $time, $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->time = $time;
        $this->description = $description;
    }

    public static function create($id, $name, $time, $description)
    {
        if (!isset(self::$idList[$id])) {
            self::$idList[$id] = new EVENT($id, $name, $time, $description);
        }
        return self::$idList[$id];
    }

    public function getID()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getParticipants()
    {
        return $this->participants;
    }

    public function getDrops()
    {
        return $this->drops;
    }

    public function getTotalValue()
    {
        return $this->totalValue;
    }

    public function getTotalParticipants()
    {
        return $this->totalParticipants;
    }

    public function setID($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setTime($time)
    {
        $this->time = $time;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function appendParticipant($user)
    {
        //Keep unique users only.
        if (!isset($this->participants[$user->getUserID()])) {
            $this->participants[$user->getUserID()] = $user;
        }
    }

    public function appendDrop($item)
    {
        //Keep Unique DROPIDs
        if (!isset($this->drops[$item->getDropID()])) {
            $this->drops[$item->getDropID()] = $item;
        }
    }

    public function setTotalValue($totalValue)
    {
        $this->totalValue = $totalValue;
    }

    public function setTotalParticipants($totalParticipants)
    {
        $this->totalParticipants = $totalParticipants;
    }
}

?>