<?php

class CollatedDrop
{
    private static $idList = array();

    private $itemID;
    private $itemName;
    private $aegisName;
    private $totalUnits = 0;
    private $soldUnits = 0;
    private $totalSoldValue = 0;

    public function __construct($itemID, $itemName, $aegisName)
    {
        $this->itemID = $itemID;
        $this->itemName = $itemName;
        $this->aegisName = $aegisName;
    }

    public static function create($itemID, $itemName, $aegisName)
    {
        if (!isset(self::$idList[$itemID])) {
            self::$idList[$itemID] = new CollatedDrop($itemID, $itemName, $aegisName);
        }
        return self::$idList[$itemID];
    }

    public function setTotalUnits($totalUnits)
    {
        $this->totalUnits = $totalUnits;
    }

    public function setSoldUnits($soldUnits)
    {
        $this->soldUnits = $soldUnits;
    }

    public function setTotalSoldValue($totalSoldValue)
    {
        $this->totalSoldValue = $totalSoldValue;
    }

    public function getItemID()
    {
        return $this->itemID;
    }

    public function getItemName()
    {
        return $this->itemName;
    }

    public function getAegisName()
    {
        return $this->aegisName;
    }

    public function getTotalUnits()
    {
        return $this->totalUnits;
    }

    public function getSoldUnits()
    {
        return $this->soldUnits;
    }

    public function getTotalSoldValue()
    {
        return $this->totalSoldValue;
    }

    public function increaseTotalUnits($increment)
    {
        $this->totalUnits += $increment;
    }

    public function increaseSoldUnit($numberSold, $price)
    {
        $this->soldUnits += $numberSold;
        $this->totalSoldValue += $price;
    }
}
