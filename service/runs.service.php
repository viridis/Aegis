<?php
require_once("../data/run.DAO.php");
require_once("../service/event.service.php");

class RunService
{
    public function listAllEvents()
    {
        $eventService = new EventService();
        return $eventService->getAllEvents();
    }

    public function addRun($name, $date)
    {
        $rundao = new RUNDAO();
        $run = $rundao->addRun($name, $date);
        return $run;
    }

    public function getRunById($eventID)
    {
        $eventService = new EventService();
        return $eventService->getAllEvents();
    }

    public function addParticipantToRun($runID, $userID)
    {
        $rundao = new RUNDAO();
        $run = $rundao->addParticipantToRun($runID, $userID);
        return $run;
    }

    public function addItemToRun($runID, $itemID)
    {
        $rundao = new RUNDAO();
        $run = $rundao->addItemToRun($runID, $itemID);
        return $run;
    }

    public function removeParticipantFromRun($runID, $userID)
    {
        $rundao = new RUNDAO();
        $run = $rundao->removeParticipantFromRun($runID, $userID);
        return $run;
    }

    public function removeItemFromRun($runID, $itemID)
    {
        $rundao = new RUNDAO();
        $run = $rundao->removeItemFromRun($runID, $itemID);
        return $run;
    }

    public function sellDrop($amount, $itemId, $value)
    {
        $rundao = new RUNDAO();
        $item = $rundao->sellDrop($amount, $itemId, $value);
        return $item;
    }
}

?>