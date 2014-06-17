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
            if (!isset($collatedDropsArray[$drop->getItemID()])) {
                $collatedDropsArray[$drop->getItemID()] = new CollatedDrop($drop->getItemID(), $drop->getItemName(), $drop->getAegisName());
            }
            /** @var CollatedDrop $collatedDrop */
            $collatedDrop = $collatedDropsArray[$drop->getItemID()];
            $collatedDrop->increaseTotalUnits(1);
            if ($drop->isSold()) {
                $collatedDrop->increaseSoldUnit(1, $drop->getSoldPrice());
            }
            $collatedDropsArray[$drop->getItemID()] = $collatedDrop;
        }
        return $collatedDropsArray;
    }

    public function processInventoryEdit()
    {
        if (isset($_POST["sellAmount"]) && isset($_POST["sellPrice"])) {
            $this->processSellItem();
        }
    }

    private function processSellItem()
    {
        $dataService = new DataService();
        $itemID = $_POST["itemID"];
        $sellAmount = $_POST["sellAmount"];
        $sellPrice = $_POST["sellPrice"];
        $soldDropsArray = $dataService->getEarliestDropsNotSoldByItemID($itemID, $sellAmount);
        try {
            foreach ($soldDropsArray as $drop) {
                /** @var Drop $drop */
                $drop->setSold(true);
                $drop->setSoldPrice($sellPrice);
                if ($drop->getHoldingUserID() != NULL) {
                    $drop->setHoldingUserID(NULL);
                }
                $dataService->updateDrop($drop);
                /** @var Event $event */
                $event = $dataService->getEventByEventID($drop->getEventID());
                $shareHoldersIDArray = $this->getEventShareholders($event);
                $payoutPerUserID = $sellPrice / (sizeof($shareHoldersIDArray) + 1); // Guild bank +1
                foreach ($shareHoldersIDArray as $userID) {
                    $this->increaseUserPayout($userID, $payoutPerUserID);
                }
            }
        } catch (Exception $e) {
            print $e->getMessage();
            return false;
        }
        $this->printJSONResponseForSuccessfulSell();
        return true;
    }


    private function increaseUserPayout($userID, $payoutPerUserID)
    {
        $dataService = new DataService();
        $user = $dataService->getUserByUserID($userID);
        $dataService->increaseUserPayout($user, $payoutPerUserID);
    }

    private function getEventShareholders($event)
    {
        /** @var Event $event */
        $shareHoldersIDArray = array();
        foreach ($event->getSlotList() as $slot) {
            /** @var Slot $slot */
            if ($slot->isTaken()) {
                array_push($shareHoldersIDArray, $slot->getTakenUserID());
            }
        }
        return $shareHoldersIDArray;
    }

    private function printJSONResponseForSuccessfulSell()
    {
        $dataService = new DataService();
        $dropsArray = $dataService->getDropsByItemID($_POST["itemID"]);
        $totalSold = 0;
        $totalSoldValue = 0;
        foreach ($dropsArray as $drop) {
            /** @var Drop $drop */
            if ($drop->isSold()) {
                $totalSold += 1;
                $totalSoldValue += $drop->getSoldPrice();
            }
        }
        if ($totalSold != 0) {
            $averageSoldPrice = $totalSoldValue / $totalSold;
        } else {
            $averageSoldPrice = 0;
        }
        $result = array(
            "itemID" => $_POST["itemID"],
            "totalSold" => $totalSold,
            "averageSoldPrice" => $averageSoldPrice
        );
        print json_encode($result);
    }
}