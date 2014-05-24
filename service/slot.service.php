<?php
require_once("../data/slot.DAO.php");

class slotService
{
    public function addSlotToEvent($event, $slotClass){
        /** @var  $event EVENT */
        $slotDAO = new SLOTDAO();
        return $slotDAO->addSlot($event->getEventID(), $slotClass);
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