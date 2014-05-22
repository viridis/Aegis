<?php
require_once("../data/slot.DAO.php");

class slotservice
{
    public static function addSlot($eventID, $slotClass){
        return SLOTDAO::addSlot($eventID, $slotClass);
    }

    public static function deleteSlot($slotID){
        return SLOTDAO::deleteSlot($slotID);
    }

    public static function getSlotByEventID($eventID){
        return SLOTDAO::getSlotByEventID($eventID);
    }
}

?>