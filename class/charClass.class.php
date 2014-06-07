<?php

class CharClass
{
    private static $idList = array();

    private $charClassID;
    private $charClassName;

    public function __construct($charClassID, $charClassName)
    {
        $this->charClassID = $charClassID;
        $this->charClassName = $charClassName;
    }

    public static function create($charClassID, $charClassName)
    {
        if (!isset(self::$idList[$charClassID])) {
            self::$idList[$charClassID] = new CharClass($charClassID, $charClassName);
        }
        return self::$idList[$charClassID];
    }

    public function getCharClassID()
    {
        return $this->charClassID;
    }

    public function getCharClassName()
    {
        return $this->charClassName;
    }
}