<?php
require_once("../data/dbconfig.DAO.php");
require_once("../class/slot.class.php");
require_once("../data/log.DAO.php");

class SLOTDAO
{
    public function addSlot($eventID, $slotClass){
        $sql = "INSERT INTO slots VALUES(:eventID, NULL, :slotClass, FALSE, NULL, NULL);";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':eventID', $eventID);
        $stmt->bindParam(':slotClass', $slotClass);
        $binds = array(
            ":eventID" => $eventID,
            ":slotClass" => $slotClass
        );
        $logdao = new LOGDAO();
        if ($stmt->execute()) {
        $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
        return true;
        } else {
            $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to add slot to eventID (' . $eventID . ')');
            return false;
        }
    }

    public function deleteSlot($slotID){
        $sql = "DELETE FROM slots WHERE slotID = $slotID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':slotID', $slotID);
        $binds = array(
            ":slotID" => $slotID
        );
        $logdao = new LOGDAO();
        if ($stmt->execute()) {
            $logdao->logPreparedStatement('DELETE', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logdao->logPreparedStatement('DELETE', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to delete slot (' . $slotID . ')');
            return false;
        }
    }

    public function getSlotByEventID($eventID){
        $result = array();
        $sqlslot = "SELECT slots.*, useraccount.userLogin, characters.charClass, characters.charName
                        FROM slots
                        LEFT JOIN useraccount ON useraccount.UserID = slots.takenUserID
                        LEFT JOIN characters ON characters.charID = slots.takenCharID
                        WHERE eventID = :eventID
                        ORDER BY eventID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlslot);
        $stmt->bindParam(':eventID', $eventID);
        $stmt->execute();
        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($resultSet as $row){
            $slot = SLOT::create($row["eventID"], $row["slotID"], $row["slotClass"], $row["taken"], $row["takenUserID"],$row["takenCharID"]);
            $slot->setUserLogin($row["userLogin"]);
            $slot->setCharClass($row["charClass"]);
            $slot->setCharName($row["charName"]);
            array_push($result, $slot);
        }
        return $result;
    }
}

?>