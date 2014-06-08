<?php

class SlotClass
{
    private static $idList = array();

    private $slotClassID;
    private $slotClassName;
    private $allowedCharClassArray = array();

    public function __construct($slotClassID, $slotClassName)
    {
        $this->slotClassID = $slotClassID;
        $this->slotClassName = $slotClassName;
    }

    public static function create($slotClassID, $slotClassName)
    {
        if (!isset(self::$idList[$slotClassID])) {
            self::$idList[$slotClassID] = new SlotClass($slotClassID, $slotClassName);
        }
        return self::$idList[$slotClassID];
    }

    public function getSlotClassID()
    {
        return $this->slotClassID;
    }

    public function getSlotClassName()
    {
        return $this->slotClassName;
    }

    public function getAllowedCharClassArray()
    {
        return $this->allowedCharClassArray;
    }

    public function setAllowedCharClassArray($allowedCharClassArray)
    {
        $this->allowedCharClassArray = $allowedCharClassArray;
    }
}