<?php
require_once("../service/data.service.php");

class ParticipateService
{
    public function getAllOpenEvents()
    {
        $dataService = new DataService();
        return $dataService->getEventByAttribute("eventState", array(0));
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
                    foreach ($gameAccount->getCharacterList() as $character) {
                        /** @var Character $character */
                        if (isset($slotClass->getAllowedCharClassArray()[$character->getCharClassID()])) {
                            array_push($result[$eventType->getEventTypeID()][$slotClass->getSlotClassID()], $character);
                        }
                    }
                }
            }
        }
        return $result;
    }
}