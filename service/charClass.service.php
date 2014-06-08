<?php
require_once("../data/charClass.DAO.php");

class CharClassService
{
    public function getAllCharClasses()
    {
        $charClassDAO = new charClassDAO();
        return $charClassDAO->getAllCharClass();
    }
}