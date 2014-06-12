<?php
require_once("../service/data.service.php");

class DropsPageService
{
    public function getAllClosedEvents()
    {
        $dataService = new DataService();
        return $dataService->getEventByAttributeValuesArray("eventState", array(1));
    }

    public function collateDropsForEvents($eventContainer)
    {
        $collatedResults = array();
        /** @var Event $event */
        foreach ($eventContainer as $event) {
            if (!isset($collatedResults[$event->getEventID()])) {
                $collatedResults[$event->getEventID()] = array();
            }
            /** @var Drop $drop */
            foreach ($event->getDropList() as $drop) {
                if (!isset($collatedResults[$event->getEventID()][$drop->getItemID()])) {
                    $collatedResults[$event->getEventID()][$drop->getItemID()] = array();
                }
                array_push($collatedResults[$event->getEventID()][$drop->getItemID()], $drop);
            }
        }
        return $collatedResults;
    }

    public function addDropToEventAJAX()
    {
        $dataService = new DataService();
        /** @var Event $event */
        $event = $dataService->getEventByEventID($_POST["eventID"]);
        $item = $dataService->getItemByItemID($_POST["addDrop"]);
        if (!$this->isClosedEvent($event)) {
            return;
        }
        try {
            $dataService->addDropToEvent($event, $item);
        } catch (Exception $e) {
            return;
        }
        $updatedEvent = $dataService->getEventByEventID($_POST["eventID"]);
        print(json_encode($updatedEvent->jsonSerialize()));

        $collatedDrops = $this->obtainCollatedDropsForEvent($event);
        $this->printCollatedDropsJSON($collatedDrops);
    }

    public function removeDropFromEventAJAX()
    {
        $dataService = new DataService();
        /** @var Event $event */
        $event = $dataService->getEventByEventID($_POST["eventID"]);
        $item = $dataService->getItemByItemID($_POST["removeDrop"]);
        if (!$this->isClosedEvent($event)) {
            return;
        }
        try {
            $dataService->addDropToEvent($event, $item);
        } catch (Exception $e) {
            return;
        }
        $updatedEvent = $dataService->getEventByEventID($_POST["eventID"]);
        print(json_encode($updatedEvent->jsonSerialize()));

        $collatedDrops = $this->obtainCollatedDropsForEvent($event);
        $this->printCollatedDropsJSON($collatedDrops);
    }

    private function isClosedEvent($event)
    {
        /** @var Event $event */
        if ($event->getEventState() == 1) {
            return true;
        } else {
            return false;
        }
    }

    private function printCollatedDropsJSON($collatedResults)
    {
        foreach ($collatedResults as $dropArray) {
            print("|");
            /** @var Drop $drop */
            $uniqueDrop = array(
                'itemName' => $dropArray[0]->getItemName(),
                'aegisName' => $dropArray[0]->getAegisName(),
                'count' => sizeof($dropArray),
                'itemID' => $dropArray[0]->getItemID()
            );
            print(json_encode($uniqueDrop));
        }
    }

    private function obtainCollatedDropsForEvent($event)
    {
        $collatedResults = array();
        foreach ($event->getDropList() as $drop) {
            if (!isset($collatedResults[$drop->getItemID()])) {
                $collatedResults[$drop->getItemID()] = array();
            }
            array_push($collatedResults[$drop->getItemID()], $drop);
        }
        return $collatedResults;
    }
}