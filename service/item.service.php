<?php
require_once("../data/item.DAO.php");

class ItemService
{
    public function listAllItems()
    {
        $itemDAO = new ItemDAO();
        return $itemDAO->getAllItems();
    }

    public function getItemByID($itemID)
    {
        $itemDAO = new ItemDAO();
        return $itemDAO->getItemByID($itemID);
    }

    public function createItem($itemID, $aegisName, $name)
    {
        $itemDAO = new ItemDAO();
        return $itemDAO->createItem($itemID, $aegisName, $name);
    }
}