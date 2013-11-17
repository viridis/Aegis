<?php
require_once("../data/item.DAO.php");

class itemservice
{
    public function listAllItems()
    {
        $itemdao = new ITEMDAO();
        $itemlist = $itemdao->getAllItems();
        return $itemlist;
    }

    public function getItemById($id)
    {
        $itemdao = new ITEMDAO();
        $item = $itemdao->getItemById($id);
        return $item;
    }

    public function addItem($name)
    {
        $itemdao = new ITEMDAO();
        $item = $itemdao->addItem($name);
        return $item;
    }

    public function updateItem($id, $name)
    {
        $itemdao = new ITEMDAO();
        $item = $itemdao->updateItem($id, $name);
        return $item;
    }
}

?>