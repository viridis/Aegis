<?php
require_once("../service/data.service.php");

class DropsPageService
{
    public function getAllClosedEvents()
    {
        $dataService = new DataService();
        return $dataService->getEventByAttributeValuesArray("eventState", array(1));
    }
}