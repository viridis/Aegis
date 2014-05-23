<?php
class DROP
{
    private static $idList = array();

    // DB fields
    private $eventID;
    private $dropID;
    private $holdingUserID;
    private $sold;
    private $soldPrice;
    private $itemID;

    // Associated fields
    private $itemName;  // From item class
    private $aegisName; // From item class

    function __construct($eventID, $dropID, $holdingUserID, $sold, $soldPrice, $itemID)
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
            self::$idList[$dropID] = new DROP($eventID, $dropID, $holdingUserID, $sold, $soldPrice, $itemID);
        }
        return self::$idList[$dropID];
    }

    public function getEventId()
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
}
?>