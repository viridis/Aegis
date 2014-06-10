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
}