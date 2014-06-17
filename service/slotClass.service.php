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

    public function getSlotClassByAttributeValuesArray($attribute, $attributeValue)
    {
        $slotClassDAO = new SlotClassDAO();
        return $slotClassDAO->getSlotClassByAttributeValuesArray($attribute, $attributeValue);
    }

    public function getSlotClassRulesByAttributeValuesArray($attribute, $attributeValue)
    {
        $slotClassDAO = new SlotClassDAO();
        return $slotClassDAO->getSlotClassRulesByAttributeValuesArray($attribute, $attributeValue);
    }

    public function updateSlotClass($slotClass)
    {
        $slotClassDAO = new SlotClassDAO();
        return $slotClassDAO->updateSlotClass($slotClass);
    }

    public function createSlotClassRule($slotClassID, $charClassID)
    {
        $slotClassDAO = new SlotClassDAO();
        return $slotClassDAO->createSlotClassRule($slotClassID, $charClassID);
    }

    public function deleteSlotClassRule($slotClassID, $charClassID)
    {
        $slotClassDAO = new SlotClassDAO();
        return $slotClassDAO->deleteSlotClassRule($slotClassID, $charClassID);
    }

    public function createSlotClass($slotClass)
    {
        $slotClassDAO = new SlotClassDAO();
        return $slotClassDAO->createSlotClass($slotClass);
    }
}