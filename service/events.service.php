<?php
require_once("../data/event.DAO.php");

class eventservice
{
    public function listAllEvents()
    {
        $eventdao = new EVENTDAO();
        $eventlist = $eventdao->getAllEvents();
        return $eventlist;
    }

    public function getEventsByParticipantID($id)
    {
        $eventdao = new EVENTDAO();
        $eventlist = $eventdao->getAllEventsByParticipantID($id);
        return $eventlist;
    }
}

?>