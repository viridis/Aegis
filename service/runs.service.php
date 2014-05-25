<?php
require_once("../service/data.service.php");

class RunService
{
    public function validCreateEvent()
    {

        return false;
    }

    public function createEventFromPostData()
    {

    }

    public function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}