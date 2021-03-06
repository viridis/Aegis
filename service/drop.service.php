<?php
require_once("../data/drop.DAO.php");
require_once("../class/events.class.php");

class DropService
{
    public function getAllDrops()
    {
        $dropDAO = new DropDAO();
        return $dropDAO->getAllDrops();
    }

    public function createDrop($drop)
    {
        $dropDAO = new DropDAO();
        return $dropDAO->createDrop($drop);
    }

    public function getDropByEventID($eventID)
    {
        $dropDAO = new DropDAO();
        return $dropDAO->getDropByEventID($eventID);
    }

    public function updateDrop($drop)
    {
        $dropDAO = new DropDAO();
        return $dropDAO->updateDrop($drop);
    }

    public function removeDrop($drop)
    {
        /** @var Drop $drop */
        $dropDAO = new DropDAO();
        return $dropDAO->removeDrop($drop->getDropID());
    }

    public function getDropByAttributeValuesArray($attribute, $attributeValue)
    {
        $dropDAO = new DropDAO();
        return $dropDAO->getDropByAttributeValuesArray($attribute, $attributeValue);
    }

    public function removeDropFromEvent($event, $item)
    {
        /** @var Item $item */
        /** @var Event $event */
        $dropDAO = new DropDAO();
        return $dropDAO->removeDropFromEvent($event->getEventID(), $item->getItemID());
    }

    public function getEarliestDropsNotSoldByItemID($itemID, $num)
    {
        $dropDAO = new DropDAO();
        return $dropDAO->getEarliestDropsNotSoldByItemID($itemID, $num);
    }

}