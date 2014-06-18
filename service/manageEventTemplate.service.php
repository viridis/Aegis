<?php

class ManageEventTemplateService
{
    public function updateEventTypeFromAJAX()
    {
        $dataService = new DataService();
        $idArray = explode("_", $_POST["id"]);

        if (isset($idArray[1])) {
            $eventTypeID = $idArray[1];
            /** @var EventType $eventType */
            $eventType = $dataService->getEventTypeByEventTypeID($eventTypeID);
            $newValue = $_POST["value"];
            if ($idArray[0] == "eventTypeName") {
                $this->updateEventTypeNameFromAJAX($eventType, $newValue);
            } else if ($idArray[0] == "characterCooldown") {
                $this->updateCharacterCooldownFromAJAX($eventType, $newValue);
            } else if ($idArray[0] == "accountCooldown") {
                $this->updateAccountCooldownFromAJAX($eventType, $newValue);
            } else if ($idArray[0] == "numSlots") {
                $this->updateNumSlotsFromAJAX($eventType, $newValue);
            }
            try {
                $dataService->updateEventType($eventType);
            } catch (Exception $e) {
                print $e->getMessage();
                return;
            }
        }
    }

    public function createNewEventTypeFromAJAX()
    {
        if (!is_string($_POST["newEventTypeName"])) {
            return;
        }
        $dataService = new DataService();
        try {
            $eventType = new EventType(NULL, $_POST["newEventTypeName"], 0, 0, 12);
            $eventTypeID = $dataService->createEventType($eventType);
        } catch (Exception $e) {
            print $e->getMessage();
            return;
        }
        /** @var EventType $newEventType */
        $newEventType = $dataService->getEventTypeByEventTypeID($eventTypeID);
        print '<tr><td class="edit" id="eventTypeName_' . $newEventType->getEventTypeID() . '">' . $newEventType->getEventName() . '</td>';
            print '<td class="edit" id="characterCooldown_' . $newEventType->getEventTypeID() .'">' . $newEventType->getCharacterCooldown() . '</td>';
        print '<td class="edit" id="accountCooldown_' . $newEventType->getEventTypeID() .'">' . $newEventType->getAccountCooldown() . '</td>';
        print '<td class="edit" id="numSlots_' . $newEventType->getEventTypeID() .'">' . $newEventType->getNumSlots() . '</td></tr>';
    }

    private function updateEventTypeNameFromAJAX($eventType, $eventTypeName)
    {
        /** @var EventType $eventType */
        $dataService = new DataService();
        $eventType->setEventName($eventTypeName);
        try {
            $dataService->updateEventType($eventType);
        } catch (Exception $e) {
            print $e->getMessage();
            return;
        }
        /** @var EventType $updatedEventType */
        $updatedEventType = $dataService->getEventTypeByEventTypeID($eventType->getEventTypeID());
        print $updatedEventType->getEventName();
    }

    private function updateCharacterCooldownFromAJAX($eventType, $characterCooldown)
    {
        if (!$this->validCooldown($characterCooldown)) {
            return;
        }
        /** @var EventType $eventType */
        $dataService = new DataService();
        $eventType->setCharacterCooldown($characterCooldown);
        try {
            $dataService->updateEventType($eventType);
        } catch (Exception $e) {
            print $e->getMessage();
            return;
        }
        /** @var EventType $updatedEventType */
        $updatedEventType = $dataService->getEventTypeByEventTypeID($eventType->getEventTypeID());
        print $updatedEventType->getCharacterCooldown();
    }

    private function updateAccountCooldownFromAJAX($eventType, $accountCooldown)
    {
        if (!$this->validCooldown($accountCooldown)) {
            return;
        }
        /** @var EventType $eventType */
        $dataService = new DataService();
        $eventType->setAccountCooldown($accountCooldown);
        try {
            $dataService->updateEventType($eventType);
        } catch (Exception $e) {
            print $e->getMessage();
            return;
        }
        /** @var EventType $updatedEventType */
        $updatedEventType = $dataService->getEventTypeByEventTypeID($eventType->getEventTypeID());
        print $updatedEventType->getAccountCooldown();
    }

    private function updateNumSlotsFromAJAX($eventType, $numSlots)
    {
        if (!$this->validNumSlots($numSlots)) {
            return;
        }
        /** @var EventType $eventType */
        $dataService = new DataService();
        $eventType->setNumSlots($numSlots);
        try {
            $dataService->updateEventType($eventType);
        } catch (Exception $e) {
            print $e->getMessage();
            return;
        }
        /** @var EventType $updatedEventType */
        $updatedEventType = $dataService->getEventTypeByEventTypeID($eventType->getEventTypeID());
        print $updatedEventType->getNumSlots();
    }

    private function validCooldown($cooldownValue)
    {
        if (!is_numeric($cooldownValue)) {
            return false;
        }
        return true;
    }

    private function validNumSlots($numSlots)
    {
        if (!is_numeric($numSlots)) {
            return false;
        }
        return true;
    }
}
