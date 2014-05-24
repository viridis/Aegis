<?php
require_once("../data/dbconfig.DAO.php");
require_once("../class/slot.class.php");
require_once("../data/log.DAO.php");

class SlotDAO
{
    public function getAllSlots()
    {
        $sqlSlots = "SELECT slots.*, useraccount.userLogin, characters.charClass, characters.charName
                        FROM slots
                        LEFT JOIN useraccount ON useraccount.UserID = slots.takenUserID
                        LEFT JOIN characters ON characters.charID = slots.takenCharID
                        ORDER BY eventID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSetSlots = $dbh->query($sqlSlots);
        $slotResults = $resultSetSlots->fetchAll(PDO::FETCH_ASSOC);
        return $slotResults;
    }

    public function createSlot($slot)
    {
        /** @var Slot $slot */
        $sqlInsert = "INSERT INTO slots VALUES(:eventID, NULL, :slotClass, FALSE, NULL, NULL);";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlInsert);
        $stmt->bindParam(':eventID', $slot->getEventID());
        $stmt->bindParam(':slotClass', $slot->getSlotClass());
        $binds = array(
            ":eventID" => $slot->getEventID(),
            ":slotClass" => $slot->getSlotClass()
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to add slot to eventID (' . $slot->getEventID() . ')');
        }
    }

    public function deleteSlot($slotID)
    {
        $sql = "DELETE FROM slots WHERE slotID = $slotID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':slotID', $slotID);
        $binds = array(
            ":slotID" => $slotID
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) {
            $logDAO->logPreparedStatement('DELETE', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logDAO->logPreparedStatement('DELETE', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to delete slot (' . $slotID . ')');
        }
    }

    public function getSlotByEventID($eventID)
    {
        $sqlSlot = "SELECT slots.*, useraccount.userLogin, characters.charClass, characters.charName
                        FROM slots
                        LEFT JOIN useraccount ON useraccount.UserID = slots.takenUserID
                        LEFT JOIN characters ON characters.charID = slots.takenCharID
                        WHERE eventID = :eventID
                        ORDER BY eventID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlSlot);
        $stmt->bindParam(':eventID', $eventID);
        $stmt->execute();
        $slotResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $slotResults;
    }

    public function updateSlot($slot)
    {
        /** @var Slot $slot */
        $sqlSlot = "UPDATE slots
                      SET eventID = :eventID,
                      slotClass = :slotClass,
                      taken = :taken,
                      takenUserID = :takenUserID,
                      takenCharID = :takenCharID
                      WHERE slotID = :slotID";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlSlot);
        $stmt->bindParam(':eventID', $slot->getEventID());
        $stmt->bindParam(':slotClass', $slot->getSlotClass());
        $stmt->bindParam(':taken', $slot->isTaken());
        $stmt->bindParam(':takenUserID', $slot->getTakenUserID());
        $stmt->bindParam(':takenCharID', $slot->getTakenCharID());
        $stmt->bindParam(':slotID', $slot->getSlotID());
        $binds = array(
            ":eventID" => $slot->getEventID(),
            ":slotClass" => $slot->getSlotClass(),
            ":taken" => $slot->isTaken(),
            ":takenUserID" => $slot->getTakenUserID(),
            ":takenCharID" => $slot->getTakenCharID(),
            ":slotID" => $slot->getSlotID()
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to update slot (' . $slot->getSlotID() . ')');
        }
    }
}