<?php
require_once("../service/data.service.php");

class RunService
{
    public function validCreateEvent()
    {
        if (empty($_POST["eventName"])) {
            return false;
        }
        if (empty($_POST["eventType"])) {
            return false;
        }
        if (empty($_POST["startDate"])) {
            return false;
        }
        if (empty($_POST["startTime"])) {
            return false;
        }
        if (empty($_POST["numSlot"])) {
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
        if ($this->validCreateEvent()) {
            $eventName = $this->test_input($_POST["eventName"]);
            $eventType = $this->test_input($_POST["eventType"]);
            $startDate = $this->test_input($_POST["startDate"]);
            $startTime = $this->test_input($_POST["startTime"]);
            $startDate = $startDate . ' ' . $startTime;
            $numSlot = $this->test_input($_POST["numSlot"]);
            $recurringEvent = $this->test_input($_POST["recurringEvent"]);
            if ($recurringEvent) {
                $dayOfWeek = $this->test_input($_POST["dayOfWeek"]);
                $hourOfDay = $this->test_input($_POST["hourOfDay"]);
            } else {
                $dayOfWeek = 0;
                $hourOfDay = 0;
            }
            try {
                $newEvent = new Event(NULL, $eventType, $startDate, NULL, NULL, $recurringEvent, $dayOfWeek, $hourOfDay, $eventName);

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


    public function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}