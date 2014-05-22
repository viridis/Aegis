<?php
require_once("../data/item.DAO.php");

class itemservice
{
    public static function listAllItems()
    {
        return ITEMDAO::getAllItems();
    }

    public static function getItemById($itemID)
    {
        return ITEMDAO::getItemById($itemID);
    }

    public static function addItem($itemID, $aegisName, $name)
    {
        return ITEMDAO::addItem($itemID, $aegisName, $name);
    }
}

?>