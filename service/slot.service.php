<?php
require_once("../data/slot.DAO.php");

class SlotService
{
    public function getAllSlots()
    {
        $slotDAO = new SlotDAO();
        return $slotDAO->getAllSlots();
    }

    public function createSlot($slot)
    {
        /** @var  $event Event */
        $slotDAO = new SlotDAO();
        return $slotDAO->createSlot($slot);
    }

    public function deleteSlot($slot)
    {
        /** @var $slot Slot */
        $slotDAO = new SlotDAO();
        return $slotDAO->deleteSlot($slot->getSlotID());
    }

    public function getSlotByEventID($eventID)
    {
        $slotDAO = new SlotDAO();
        return $slotDAO->getSlotByEventID($eventID);
    }

    public function updateSlot($slot)
    {
        $slotDAO = new SlotDAO();
        return $slotDAO->updateSlot($slot);
    }

    public function getSlotByAttributeValuesArray($attribute, $attributeValue){
        $slotDAO = new SlotDAO();
        return $slotDAO->getSlotByAttributeValuesArray($attribute, $attributeValue);
    }

}