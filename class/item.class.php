<?php
class Item
{
    private static $idList = array();
    private $itemID;
    private $aegisName;
    private $name;


    public function __construct($itemID, $aegisName, $name)
    {
        $this->itemID = $itemID;
        $this->aegisName = $aegisName;
        $this->name = $name;
    }

    public static function create($itemID, $aegisName, $name)
    {
        if (!isset(self::$idList[$itemID])) {
            self::$idList[$itemID] = new Item($itemID, $aegisName, $name);
        }
        return self::$idList[$itemID];
    }

    public function getItemID()
    {
        return $this->itemID;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAegisName()
    {
        return $this->aegisName;
    }

    public function setId($itemID)
    {
        $this->itemID = $itemID;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setTalonID($aegisName)
    {
        $this->aegisName = $aegisName;
    }
}

?>