<?php
require_once("../data/drop.DAO.php");
require_once("../class/events.class.php");

class dropservice
{
    public function addDropToEvent($event, $item){
        $dropDAO = new DROPDAO();
        /** @var $event EVENT */
        /** @var $item ITEM */
        return $dropDAO->addDrop($event->getEventID(), $item->getItemID());
    }

    public function getDropByEventID($eventID){
        $dropDAO = new DROPDAO();
        return $dropDAO->getDropByEventID($eventID);
    }
}

?>