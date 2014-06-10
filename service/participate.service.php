<?php
require_once("../service/data.service.php");

class ParticipateService
{
    public function getAllOpenEvents()
    {
        $dataService = new DataService();
        return $dataService->getEventByAttributeValuesArray("eventState", array(0));
    }

    public function getValidCharactersForSlotClassID()
    {
        $result = array();
        $dataService = new DataService();
        $slotClasses = $dataService->getAllSlotClasses();
        $eventTypes = $dataService->getAllEventTypes();
        /** @var User $currentUser */
        $currentUser = $dataService->getUserByUserID($_SESSION["userID"]);
        foreach ($eventTypes as $eventType) {
            /** @var EventType $eventType */
            foreach ($slotClasses as $slotClass) {
                /** @var SlotClass $slotClass */
                $result[$eventType->getEventTypeID()][$slotClass->getSlotClassID()] = array();
                foreach ($currentUser->getGameAccountContainer() as $gameAccount) {
                    /** @var GameAccount $gameAccount */
                    $onCooldown = false;
                    foreach ($gameAccount->getCooldownContainer() as $cooldown) {
                        /** @var Cooldown $cooldown */
                        if ($cooldown->getEventTypeID() == $eventType->getEventTypeID()) {
                            $onCooldown = true;
                        }
                    }
                    if (!$onCooldown) {
                        foreach ($gameAccount->getCharacterList() as $character) {
                            /** @var Character $character */
                            $charOnCooldown = false;
                            foreach ($character->getCooldownContainer() as $cooldown) {
                                /** @var Cooldown $cooldown */
                                if ($cooldown->getEventTypeID() == $eventType->getEventTypeID()) {
                                    $charOnCooldown = true;
                                }
                            }
                            if (!$charOnCooldown) {
                                if (isset($slotClass->getAllowedCharClassArray()[$character->getCharClassID()])) {
                                    array_push($result[$eventType->getEventTypeID()][$slotClass->getSlotClassID()], $character);
                                }
                            }
                        }
                    }
                }
            }
        }


        return $result;
    }

    public function setSlotTaken()
    {
        $takenCharID = $_POST["join_slot_" . $_GET["updateSlot"]];
        $slotID = $_GET["updateSlot"];
        $dataService = new DataService();
        /** @var Character $character */
        $character = $dataService->getCharacterByCharID($takenCharID);
        /** @var Slot $slot */
        $slot = $dataService->getSlotBySlotID($slotID);
        if (!$this->isValidPairing($character, $slot)) {
            return false;
        }
        $slot->setTaken(true);
        $slot->setTakenCharID($takenCharID);
        $slot->setTakenUserID($character->getUserID());
        try {
            $dataService->updateSlot($slot);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    private function isValidPairing($character, $slot)
    {
        /** @var Slot $slot */
        /** @var Character $character */
        $dataService = new DataService();
        /** @var Event $event */
        $event = $dataService->getEventByEventID($slot->getEventID());

        // Check for cooldown for eventType
        $eventDate = strtotime($event->getStartDate());
        $cooldownDate = 0;
        if (isset($character->getCooldownContainer()[$event->getEventTypeID()])) {
            $cooldownDate = strtotime($character->getCooldownContainer()[$event->getEventTypeID()]);
        }
        if ($cooldownDate > $eventDate){
            return false;
        }

        // Check for slotClass restriction
        /** @var SlotClass $slotClass */
        $slotClass = $dataService->getSlotClassBySlotClassID($slot->getSlotClassID());
        if (!isset($slotClass->getAllowedCharClassArray()[$character->getCharClassID()])){
            return false;
        }
        return true;
    }
}