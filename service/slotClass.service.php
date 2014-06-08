<?php
require_once("../data/slotClass.DAO.php");

class SlotClassService
{
    public function getAllSlotClasses()
    {
        $slotClassDAO = new SlotClassDAO();
        return $slotClassDAO->getAllSlotClasses();
    }

    public function getAllSlotClassRules()
    {
        $slotClassDAO = new SlotClassDAO();
        return $slotClassDAO->getAllSlotClassRules();
    }
}