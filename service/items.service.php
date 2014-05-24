<?php
require_once("../data/item.DAO.php");

class itemService
{
    public function listAllItems()
    {
        $itemDAO = new ITEMDAO();
        return $itemDAO->getAllItems();
    }

    public function getItemByID($itemID)
    {
        $itemDAO = new ITEMDAO();
        return $itemDAO->getItemByID($itemID);
    }

    public function createItem($itemID, $aegisName, $name)
    {
        $itemDAO = new ITEMDAO();
        return $itemDAO->createItem($itemID, $aegisName, $name);
    }
}