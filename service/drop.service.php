<?php
require_once("../data/drop.DAO.php");

class dropservice
{
    public function addDrop($eventID, $itemID){
        $dropDAO = new DROPDAO();
        return $dropDAO->addDrop($eventID, $itemID);
    }

    public function getDropByEventID($eventID){
        $dropDAO = new DROPDAO();
        return $dropDAO->getDropByEventID($eventID);
    }
}

?>