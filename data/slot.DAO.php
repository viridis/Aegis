<?php
require_once("../data/dbconfig.DAO.php");
require_once("../class/slot.class.php");
require_once("../data/log.DAO.php");

class SlotDAO
{
    public function getAllSlots()
    {
        $sqlSlots = "SELECT slots.*, useraccount.userLogin, characters.charClassID, characters.charName,
                        characters.accountID, slotClasses.slotClassName, charClasses.charClassName
                        FROM slots
                        LEFT JOIN useraccount ON useraccount.UserID = slots.takenUserID
                        LEFT JOIN characters ON characters.charID = slots.takenCharID
                        LEFT JOIN slotClasses ON slotClasses.slotClassID = slots.slotClassID
                        LEFT JOIN charClasses ON charClasses.charClassID = slots.takenCharClassID
                        ORDER BY eventID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSetSlots = $dbh->query($sqlSlots);
        $slotResults = $resultSetSlots->fetchAll(PDO::FETCH_ASSOC);
        return $slotResults;
    }

    public function getSlotByAttributeValuesArray($attribute, $attributeValue)
    {
        $sqlSlot = "SELECT slots.*, useraccount.userLogin, characters.charClassID, characters.charName, characters.accountID, slotClasses.slotClassName, charClasses.charClassName
                        FROM slots
                        LEFT JOIN useraccount ON useraccount.UserID = slots.takenUserID
                        LEFT JOIN characters ON characters.charID = slots.takenCharID
                        LEFT JOIN slotClasses ON slotClasses.slotClassID = slots.slotClassID
                        LEFT JOIN charClasses ON charClasses.charClassID = slots.takenCharClassID
                       WHERE " . $attribute . " = '" . $attributeValue[0] . "' ";
        if (count($attributeValue) > 1) {
            array_shift($attributeValue);
            foreach ($attributeValue as $value) {
                $sqlSlot .= "OR " . $attribute . " = '" . $value . "'";
            }
        }
        $sqlSlot .= "ORDER BY eventID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSetSlots = $dbh->query($sqlSlot);
        $slotResults = $resultSetSlots->fetchAll(PDO::FETCH_ASSOC);
        return $slotResults;
    }


    public function createSlot($slot)
    {
        /** @var Slot $slot */
        $sqlInsert = "INSERT INTO slots VALUES(:eventID, NULL, :slotClassID, FALSE, NULL, NULL, NULL);";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlInsert);
        $eventID = $slot->getEventID();
        $stmt->bindParam(':eventID', $eventID);
        $slotClassID = $slot->getSlotClassID();
        $stmt->bindParam(':slotClassID', $slotClassID);
        $binds = array(
            ":eventID" => $eventID,
            ":slotClassID" => $slotClassID
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to add slot to eventID (' . $eventID . ')');
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
        $sqlSlot = "SELECT slots.*, useraccount.userLogin, characters.charClassID, characters.charName, characters.accountID, slotClasses.slotClassName, charClasses.charClassName
                        FROM slots
                        LEFT JOIN useraccount ON useraccount.UserID = slots.takenUserID
                        LEFT JOIN characters ON characters.charID = slots.takenCharID
                        LEFT JOIN slotClasses ON slotClasses.slotClassID = slots.slotClassID
                        LEFT JOIN charClasses ON charClasses.charClassID = slots.takenCharClassID
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
                      slotClassID = :slotClassID,
                      taken = :taken,
                      takenUserID = :takenUserID,
                      takenCharID = :takenCharID,
                      takenCharClassID = :takenCharClassID
                      WHERE slotID = :slotID";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlSlot);
        $eventID = $slot->getEventID();
        $stmt->bindParam(':eventID', $eventID);
        $slotClassID = $slot->getSlotClassID();
        $stmt->bindParam(':slotClassID', $slotClassID);
        $isTaken = $slot->isTaken();
        $stmt->bindParam(':taken', $isTaken);
        $takenUserID = $slot->getTakenUserID();
        $stmt->bindParam(':takenUserID', $takenUserID);
        $takenCharID = $slot->getTakenCharID();
        $stmt->bindParam(':takenCharID', $takenCharID);
        $takenCharClassID = $slot->getTakenCharClassID();
        $stmt->bindParam(':takenCharClassID', $takenCharClassID);
        $slotID = $slot->getSlotID();
        $stmt->bindParam(':slotID', $slotID);
        $binds = array(
            ":eventID" => $eventID,
            ":slotClassID" => $slotClassID,
            ":taken" => $isTaken,
            ":takenUserID" => $takenUserID,
            ":takenCharID" => $takenCharID,
            ":takenCharClassID" => $takenCharClassID,
            ":slotID" => $slotID
        );
        $logDAO = new LogDAO();
        if ($stmt->execute()) {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logDAO->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to update slot (' . $slotID . ')');
        }
    }
}