<?php
class PARTICIPANT
{
    private static $idList = array();
    private $participantID;
    private $userID;
    private $name;
    private $mailname;
    private $paidOut;

    function __construct($participantID, $userID, $name, $mailname, $paidOut)
    {
        $this->participantID = $participantID;
        $this->userID = $userID;
        $this->name = $name;
        $this->mailname = $mailname;
        $this->paidOut = $paidOut;
    }

    public static function create($participantID, $userID, $name, $mailname, $paidOut)
    {
        if (!isset(self::$idList[$participantID])) {
            self::$idList[$participantID] = new PARTICIPANT($participantID, $userID, $name, $mailname, $paidOut);
        }
        return self::$idList[$participantID];
    }

    public function getParticipantID()
    {
        return $this->participantID;
    }

    public function getUserID()
    {
        return $this->userID;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getMailName()
    {
        return $this->mailname;
    }

    public function getPaidOut()
    {
        return $this->paidOut;
    }

    public function setParticipantID($id)
    {
        $this->participantID = $id;
    }

    public function setUserID($id)
    {
        $this->userID = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setMailName($mailname)
    {
        $this->mailname = $mailname;
    }

    public function setPaidOut($value)
    {
        $this->paidOut = $value;
    }
}

?>