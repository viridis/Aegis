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
        $dataService = new DataService();
        $slotClasses = $dataService->getAllSlotClasses();
        return $slotClasses;
    }

}