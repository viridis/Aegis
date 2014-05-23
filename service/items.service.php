<?php
require_once("../data/item.DAO.php");

class itemservice
{
    public function listAllItems()
    {
        $itemDAO = new ITEMDAO();
        return $itemDAO->getAllItems();
    }

    public function getItemById($itemID)
    {
        $itemDAO = new ITEMDAO();
        return $itemDAO->getItemById($itemID);
    }

    public function addItem($itemID, $aegisName, $name)
    {
        $itemDAO = new ITEMDAO();
        return $itemDAO->addItem($itemID, $aegisName, $name);
    }
}

?>