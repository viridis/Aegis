<?php

require_once("../service/events.service.php");

$eventservice = new eventservice();
$eventsByUserID = $eventservice->getEventsByParticipantID(1);
$event = $eventsByUserID[15]->getParticipants()[5]->getPaidOut();
var_dump($event);
?>