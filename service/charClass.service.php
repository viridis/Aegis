<?php
require_once("../data/charClass.DAO.php");

class CharClassService
{
    public function getAllCharClass()
    {
        $charClassDAO = new charClassDAO();
        return $charClassDAO->getAllCharClass();
    }
}