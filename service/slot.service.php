<?php
require_once("../data/slot.DAO.php");

class slotservice
{
    public function addSlot($eventID, $slotClass){
        $slotDAO = new SLOTDAO();
        return $slotDAO->addSlot($eventID, $slotClass);
    }

    public function deleteSlot($slotID){
        $slotDAO = new SLOTDAO();
        return $slotDAO->deleteSlot($slotID);
    }

    public function getSlotByEventID($eventID){
        $slotDAO = new SLOTDAO();
        return $slotDAO->getSlotByEventID($eventID);
    }
}
?>