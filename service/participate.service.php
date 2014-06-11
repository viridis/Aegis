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
        $slotID = $_GET["updateSlot"];
        $takenCharID = $_POST["join_slot_" . $slotID];
        $dataService = new DataService();
        /** @var Character $character */
        $character = $dataService->getCharacterByCharID($takenCharID);
        /** @var Slot $slot */
        $slot = $dataService->getSlotBySlotID($slotID);
        if (!$this->isValidJoinEvent($character, $slot)) {
            return false;
        }
        $slot->setTaken(true);
        $slot->setTakenCharID($takenCharID);
        $slot->setTakenUserID($character->getUserID());
        try {
            $dataService->updateSlot($slot);
        } catch (Exception $e) {
            print $e->getMessage();
            return false;
        }
        return true;
    }

    public function updateCharacterInSlot()
    {
        $slotID = $_GET["updateSlot"];
        $newTakenCharID = $_POST["change_slot_" . $slotID];
        $dataService = new DataService();
        $newTakenChar = $dataService->getCharacterByCharID($newTakenCharID);
        /** @var Slot $slot */
        $slot = $dataService->getSlotBySlotID($slotID);
        if (!$this->isValidChangeCharacterInSlot($newTakenChar, $slot)) {
            return false;
        }
        return $this->changeCharacterInSlot($newTakenChar, $slot);
    }

    public function vacateCharacterFromSlot()
    {
        $slotID = $_GET["updateSlot"];
        $dataService = new DataService();
        $slot = $dataService->getSlotBySlotID($slotID);
        if ($this->sessionUserOwnsSlot($slot) || $this->sessionUserHasPrivileges()) {
            return $this->vacateSlot($slot);
        }
        return false;
    }

    private function changeCharacterInSlot($newTakenChar, $slot)
    {
        /** @var Slot $slot */
        /** @var Character $newTakenChar */
        $slot->setTakenCharID($newTakenChar->getCharID());
        $slot->setTakenUserID($newTakenChar->getUserID());
        $dataService = new DataService();
        try {
            $dataService->updateSlot($slot);
        } catch (Exception $e) {
            print $e->getMessage();
            return false;
        }
        return true;
    }

    private function vacateSlot($slot)
    {
        /** @var Slot $slot */
        $dataService = new DataService();

        $slot->setTaken(false);
        $slot->setTakenCharID(NULL);
        $slot->setTakenUserID(NULL);
        try {
            $dataService->updateSlot($slot);
        } catch (Exception $e) {
            print $e->getMessage();
            return false;
        }
        return true;
    }

    private function isValidChangeCharacterInSlot($newTakenChar, $slot)
    {
        /** @var Slot $slot */
        if (!$this->sessionUserOwnsSlot($slot) && !$this->sessionUserHasPrivileges()) {
            return false;
        }

        if (!$this->sessionUserHasRightsToCharacter($newTakenChar)) {
            return false;
        }

        return true;
    }

    private function sessionUserOwnsSlot($slot)
    {
        /** @var Slot $slot */
        if ($_SESSION["userID"] == $slot->getTakenUserID()) {
            return true;
        } else {
            return false;
        }
    }

    private function sessionUserHasPrivileges()
    {
        $dataService = new DataService();
        $userID = $_SESSION["userID"];
        /** @var User $user */
        $user = $dataService->getUserByUserID($userID);
        if ($user->getRoleLevel() == 10) {
            return true;
        } else {
            return false;
        }
    }

    private function isValidJoinEvent($character, $slot)
    {
        /** @var Slot $slot */
        /** @var Character $character */
        $dataService = new DataService();
        /** @var Event $event */
        $event = $dataService->getEventByEventID($slot->getEventID());
        if (!$this->sessionUserHasRightsToCharacter($character)) {
            return false;
        }
        if ($this->characterOnCooldownForEvent($character, $event)) {
            return false;
        }
        if (!$this->characterCanFitSlot($character, $slot, $dataService)) {
            return false;
        }
        return true;
    }

    private function sessionUserHasRightsToCharacter($character)
    {
        /** @var Character $character */
        $dataService = new DataService();
        /** @var User $charUser */
        $charUser = $dataService->getUserByUserID($character->getUserID());
        /** @var User $sessionUser */
        $sessionUser = $dataService->getUserByUserID($_SESSION["userID"]);
        if (($charUser->getUserID() != $sessionUser->getUserID()) && !$this->sessionUserHasPrivileges()) {
            return false;
        } else {
            return true;
        }
    }

    private function characterOnCooldownForEvent($character, $event)
    {
        /** @var Event $event */
        /** @var Character $character */
        $eventDate = strtotime($event->getStartDate());
        $cooldownDate = 0;
        if (isset($character->getCooldownContainer()[$event->getEventTypeID()])) {
            $cooldownDate = strtotime($character->getCooldownContainer()[$event->getEventTypeID()]);
        }
        if ($cooldownDate > $eventDate) {
            return true;
        } else {
            return false;
        }
    }

    private function characterCanFitSlot($character, $slot)
    {
        $dataService = new DataService();
        /** @var Slot $slot */
        /** @var Character $character */
        /** @var SlotClass $slotClass */
        $slotClass = $dataService->getSlotClassBySlotClassID($slot->getSlotClassID());
        if (isset($slotClass->getAllowedCharClassArray()[$character->getCharClassID()])) {
            return true;
        } else {
            return false;
        }
    }
}