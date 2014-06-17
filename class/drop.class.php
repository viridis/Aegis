<?php
class Drop
{
    private static $idList = array();

    private $eventID;
    private $dropID;
    private $holdingUserID;
    private $sold;
    private $soldPrice;
    private $itemID;

    // Associated fields
    private $itemName;  // From item class
    private $aegisName; // From item class

    public function __construct($eventID, $dropID, $holdingUserID, $sold, $soldPrice, $itemID)
    {
        $this->eventID = $eventID;
        $this->dropID = $dropID;
        $this->holdingUserID = $holdingUserID;
        $this->sold = $sold;
        $this->soldPrice = $soldPrice;
        $this->itemID = $itemID;
    }

    public static function create($eventID, $dropID, $holdingUserID, $sold, $soldPrice, $itemID)
    {
        if (!isset(self::$idList[$dropID])) {
            self::$idList[$dropID] = new Drop($eventID, $dropID, $holdingUserID, $sold, $soldPrice, $itemID);
        }
        return self::$idList[$dropID];
    }

    public function getEventID()
    {
        return $this->eventID;
    }

    public function getDropID(){
        return $this->dropID;
    }

    public function getHoldingUserID(){
        return $this->holdingUserID;
    }

    public function isSold(){
        return $this->sold;
    }

    public function getSoldPrice(){
        return $this->soldPrice;
    }

    public function getItemID(){
        return $this->itemID;
    }

    public function getItemName(){
        return $this->itemName;
    }

    public function getAegisName(){
        return $this->aegisName;
    }

    public function setItemName($itemName){
        $this->itemName = $itemName;
    }

    public function setAegisName($aegisName){
        $this->aegisName = $aegisName;
    }

    public function setSold($isSold)
    {
        $this->sold = $isSold;
    }

    public function setSoldPrice($soldPrice)
    {
        $this->soldPrice = $soldPrice;
    }

    public function setHoldingUserID($holdingUserID)
    {
        $this->holdingUserID = $holdingUserID;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }


}