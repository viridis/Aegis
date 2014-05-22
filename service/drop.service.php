<?php
require_once("../data/drop.DAO.php");

class dropservice
{
    public static function addDrop($eventID, $itemID){
        return DROPDAO::addDrop($eventID, $itemID);
    }

    public static function getDropByEventID($eventID){
        return DROPDAO::getDropByEventID($eventID);
    }
}

?>