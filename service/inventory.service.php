<?php
require_once("../service/data.service.php");
require_once("../class/collatedDrop.class.php");

class InventoryService
{
    public function getAllCollatedDrops()
    {
        $collatedDropsArray = array();
        $dataService = new DataService();
        $allDropsArray = $dataService->getAllDrops();
        foreach ($allDropsArray as $drop) {
            /** @var Drop $drop */
            if (!isset($collatedDropsArray[$drop->getItemID()])){
                $collatedDropsArray[$drop->getItemID()] = new CollatedDrop($drop->getItemID(), $drop->getItemName(), $drop->getAegisName());
            }
            /** @var CollatedDrop $collatedDrop */
            $collatedDrop = $collatedDropsArray[$drop->getItemID()];
            $collatedDrop->increaseTotalUnits(1);
            if ($drop->isSold()){
                $collatedDrop->increaseSoldUnit(1, $drop->getSoldPrice());
            }
            $collatedDropsArray[$drop->getItemID()] = $collatedDrop;
        }
        return $collatedDropsArray;
    }
}