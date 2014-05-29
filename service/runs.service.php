<?php
require_once("../service/data.service.php");

class RunService
{
    public function validEventData()
    {
        if (empty($_POST["eventTypeID"])) {
            return false;
        }
        if (empty($_POST["startDate"])) {
            return false;
        }
        if (empty($_POST["startTime"])) {
            return false;
        }
        if (!empty($_POST["recurringEvent"])) {
            if (empty($_POST["dayOfWeek"]) || empty($_POST["hourOfDay"])) {
                return false;
            }
        }
        return true;
    }

    public function createEventFromPostData()
    {
        $dataService = new DataService();

        if ($this->validEventData()) {
            $eventTypeID = $this->test_input($_POST["eventTypeID"]);
            $startDate = $this->test_input($_POST["startDate"]);
            $startTime = $this->test_input($_POST["startTime"]);
            $startDate = $startDate . ' ' . $startTime;
            /** @var EventType $eventType */
            $eventType = $dataService->getEventTypeByEventTypeID($eventTypeID);
            $numSlot = $eventType->getNumSlots();
            if (isset($_POST["recurringEvent"])) {
                $recurringEvent = $this->test_input($_POST["recurringEvent"]);
                $dayOfWeek = $this->test_input($_POST["dayOfWeek"]);
                $hourOfDay = $this->test_input($_POST["hourOfDay"]);
            } else {
                $recurringEvent = false;
                $dayOfWeek = 0;
                $hourOfDay = 0;
            }
            try {
                $newEvent = new Event(NULL, $eventTypeID, $startDate, NULL, NULL, $recurringEvent, $dayOfWeek, $hourOfDay);
                $eventID = $dataService->createEvent($newEvent);
                $event = $dataService->getEventByEventID($eventID);
                for ($i = 0; $i < $numSlot; $i++) {
                    $dataService->addSlotToEvent($event, 0);
                }
            } catch (Exception $e) {
                echo 'Caught exception' . $e->getMessage();
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    public function updateEventFromPostData()
    {
        $dataService = new DataService();
        if ($this->validEventData()) {
            /** @var Event $event */
            $event = $dataService->getEventByEventID($_GET["editRun"]);
            $event->setEventTypeID($this->test_input($_POST["eventTypeID"]));
            $startDate = $this->test_input($_POST["startDate"]);
            $startTime = $this->test_input($_POST["startTime"]);
            $startDate = $startDate . ' ' . $startTime;
            $event->setStartDate($startDate);
            if (isset($_POST["recurringEvent"])) {
                $event->setRecurringEvent($this->test_input($_POST["recurringEvent"]));
                $event->setDayOfWeek($this->test_input($_POST["dayOfWeek"]));
                $event->setHourOfDay($this->test_input($_POST["hourOfDay"]));
            } else {
                $event->setRecurringEvent(false);
                $event->setDayOfWeek(0);
                $event->setHourOfDay(0);
            }
            try {
                $dataService->updateEvent($event);
                foreach ($event->getSlotList() as $slot) {
                    /** @var Slot $slot */
                    $slot->setSlotClassID($this->test_input($_POST["slotEdit_" . $slot->getSlotID()]));
                    $dataService->updateSlot($slot);
                }
            } catch (Exception $e) {
                echo 'Caught exception' . $e->getMessage();
                return false;
            }
            return true;
        }
        return false;
    }

    public function closeEventFromPostData()
    {
        $dataService = new DataService();
        $eventID = $this->test_input($_POST["eventID"]);
        /** @var Event $event */
        $event = $dataService->getEventByEventID($eventID);
        $event->setCompleteDate(date("Y-m-d H:i:s"));
        $event->setEventState(1);
        try {
            $dataService->updateEvent($event);
            $this->createCooldownsForEvent($event);
            if ($event->isRecurringEvent()){
                $this->duplicateRecurringClosedEvent($event);
            }
        } catch (Exception $e) {
            echo 'Caught exception' . $e->getMessage();
            return false;
        }
        return true;
    }

    public function openEventFromPostData()
    {
        $dataService = new DataService();
        $eventID = $this->test_input($_POST["eventID"]);
        /** @var Event $event */
        $event = $dataService->getEventByEventID($eventID);
        $event->setCompleteDate(NULL);
        $event->setEventState(0);
        try {
            $dataService->updateEvent($event);
            $this->removeCooldownsForEvent($event);
        } catch (Exception $e) {
            echo 'Caught exception' . $e->getMessage();
            return false;
        }
        return true;
    }

    private function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    private function createCooldownsForEvent($event)
    {
        $dataService = new DataService();
        /** @var Event $event */
        if ($event->getAccountCooldown() > 0) {
            $this->createCharacterCooldownsForEvent($event, $dataService);
        }
        if ($event->getCharacterCooldown() > 0) {
            $this->createAccountCooldownsForEvent($event, $dataService);
        }
    }

    private function createCharacterCooldownsForEvent($event)
    {
        /** @var Event $event */
        $dataService = new DataService();
        $endDate = date("Y-m-d H:i:s", time() + $event->getAccountCooldown());
        foreach ($event->getSlotList() as $slot) {
            /** @var Slot $slot */
            if ($slot->isTaken()) {
                $cooldown = new Cooldown(NULL, $event->getEventID(), $slot->getAccountID(), NULL, $endDate, $event->getEventTypeID());
                $dataService->createCooldown($cooldown);
            }
        }
    }

    private function createAccountCooldownsForEvent($event)
    {
        /** @var Event $event */
        $dataService = new DataService();
        $endDate = date("Y-m-d H:i:s", time() + $event->getCharacterCooldown());
        foreach ($event->getSlotList() as $slot) {
            /** @var Slot $slot */
            if ($slot->isTaken()) {
                $cooldown = new Cooldown(NULL, $event->getEventID(), NULL, $slot->getTakenCharID(), $endDate, $event->getEventTypeID());
                $dataService->createCooldown($cooldown);
            }
        }
    }

    private function removeCooldownsForEvent($event)
    {
        $dataService = new DataService();
        $cooldownContainer = $dataService->getCooldownByEvent($event);
        foreach ($cooldownContainer as $cooldown) {
            $dataService->deleteCooldown($cooldown);
        }
    }

    private function duplicateRecurringClosedEvent($event)
    {
        /** @var Event $event */
        $dataService = new DataService();
        $date = new DateTime($event->getStartDate());
        $newStartDate = $date->add(new DateInterval('P7D'));
        $formattedNewStartDate = $newStartDate->format('Y-m-d H:i:s');
        $newEvent = new Event(NULL, $event->getEventTypeID(), $formattedNewStartDate, NULL, NULL, true, $event->getDayOfWeek(), $event->getHourOfDay());
        $dataService->createEvent($newEvent);
    }
}