<?php
require_once("../service/data.service.php");

class ManageSlotsService
{
    public function updateSlotClassRuleFromAJAX()
    {
        $idArray = explode("_", $_POST["id"]);
        if ($idArray[0] == "slotClassName" && isset($idArray[1])) {
            $this->updateSlotClassName($idArray[1]);
        } else if ($idArray[0] == "slotClassRule" && isset($idArray[1]) && isset($idArray[2])) {
            $this->updateSlotClassRule($idArray[1], $idArray[2]);
        }
    }

    private function updateSlotClassName($slotClassID)
    {
        $dataService = new DataService();
        /** @var SlotClass $slotClass */
        $slotClass = $dataService->getSlotClassBySlotClassID($slotClassID);
        $slotClass->setSlotClassName($_POST["value"]);
        $dataService->updateSlotClass($slotClass);
        /** @var SlotClass $updatedSlotClass */
        $updatedSlotClass = $dataService->getSlotClassBySlotClassID($slotClassID);
        print ($updatedSlotClass->getSlotClassName());
    }

    private function updateSlotClassRule($slotClassID, $charClassID)
    {
        $dataService = new DataService();
        if (strtoupper($_POST["value"]) == "Y") {
            $dataService->createSlotClassRule($slotClassID, $charClassID);
        } else if (strtoupper($_POST["value"]) == "N") {
            $dataService->deleteSlotClassRule($slotClassID, $charClassID);
        }
        /** @var SlotClass $updatedSlotClass */
        $updatedSlotClass = $dataService->getSlotClassBySlotClassID($slotClassID);
        isset($updatedSlotClass->getAllowedCharClassArray()[$charClassID]) ? print "Y" : print "N";
    }

    public function createNewSlotClassFromAJAX()
    {
        if (!is_string($_POST["newSlotClassName"])) {
            return;
        }
        $dataService = new DataService();
        try {
            $slotClass = new SlotClass(NULL, $_POST["newSlotClassName"]);
            $newSlotClassID = $dataService->createSlotClass($slotClass);
        } catch (Exception $e) {
            return;
        }
        /** @var SlotClass $newSlotClass */
        $newSlotClass = $dataService->getSlotClassBySlotClassID($newSlotClassID);
        $charClassArray = $dataService->getAllCharClasses();
        print '<tr><td class="edit" id="slotClassName_' . $newSlotClass->getSlotClassID() . '">' . $newSlotClass->getSlotClassName() . '</td>';
        foreach ($charClassArray as $charClass) {
            print '<td class="edit" id="';
            print 'slotClassRule_' . $newSlotClass->getSlotClassID() . '_' . $charClass->getCharClassID() . '">';
            isset($slotClass->getAllowedCharClassArray()[$charClass->getCharClassID()]) ? print 'Y' : print  'N';
            print '</td>';
        }
        print '</tr>';
    }
}