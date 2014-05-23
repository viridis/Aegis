<?php
require_once("../data/dbconfig.DAO.php");
require_once("../class/events.class.php");
require_once("../class/drop.class.php");
require_once("../class/slot.class.php");

class EVENTDAO
{
    public function getAllEvents()
    {
        $sqlevents = "SELECT * FROM events ORDER BY eventID ASC;";
        $sqldrops = "SELECT drops.*, items.name, items.aegisName
                        FROM drops
                        LEFT JOIN items ON items.itemID = drops.itemID
                        ORDER BY eventID ASC;";
        $sqlslots = "SELECT slots.*, useraccount.userLogin, characters.charClass, characters.charName
                        FROM slots
                        LEFT JOIN useraccount ON useraccount.UserID = slots.takenUserID
                        LEFT JOIN characters ON characters.charID = slots.takenCharID
                        ORDER BY eventID ASC;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSetEvents = $dbh->query($sqlevents);
        $resultSetDrops = $dbh->query($sqldrops);
        $resultSetSlots = $dbh->query($sqlslots);
        $eventResults = $resultSetEvents->fetchAll();
        $dropResults = $resultSetDrops->fetchAll();
        $slotResults = $resultSetSlots->fetchAll();
        $result = $this->createEventArray($eventResults, $dropResults, $slotResults);
        return $result;
    }

    public function getEventByID($eventID){
        $sqlevent = "SELECT * FROM events WHERE eventID = :eventID;";
        $sqldrops = "SELECT drops.*, items.name, items.aegisName
                        FROM drops
                        LEFT JOIN items ON items.itemID = drops.itemID
                        WHERE eventID = :eventID;";
        $sqlslots = "SELECT slots.*, useraccount.userLogin, characters.charClass, characters.charName
                        FROM slots
                        LEFT JOIN useraccount ON useraccount.UserID = slots.takenUserID
                        LEFT JOIN characters ON characters.charID = slots.takenCharID
                        WHERE eventID = :eventID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sqlevent);
        $stmt->bindParam(':eventID', $eventID);
        $stmt->execute();
        $eventResults = $stmt->fetchAll();
        $stmt = $dbh->prepare($sqldrops);
        $stmt->bindParam(':eventID', $eventID);
        $stmt->execute();
        $dropResults = $stmt->fetchAll();
        $stmt = $dbh->prepare($sqlslots);
        $stmt->bindParam(':eventID', $eventID);
        $stmt->execute();
        $slotResults = $stmt->fetchAll();
        $result = $this->createEventArray($eventResults, $dropResults, $slotResults);
        return $result;
    }

    public function addEvent($eventType, $startDate, $eventName, $recurringEvent = 0, $dayOfWeek = 0, $hourOfDay = 0){
        $sql = "INSERT INTO events VALUES(NULL, :eventType, :startDate, NULL, 0, :recurringEvent, :dayOfWeek, :hourOfDay, :eventName);";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':eventType', $eventType);
        $stmt->bindParam(':startDate', $startDate);
        $stmt->bindParam(':eventName', $eventName);
        $stmt->bindParam(':recurringEvent', $recurringEvent);
        $stmt->bindParam(':dayOfWeek', $dayOfWeek);
        $stmt->bindParam(':hourOfDay', $hourOfDay);
        $binds = array(
            ":eventType" => $eventType,
            ":startDate" => $startDate,
            ":recurringEvent" => $recurringEvent,
            ":dayOfWeek" => $dayOfWeek,
            ":hourOfDay" => $hourOfDay,
            ":eventName" => $eventName
        );
        $logdao = new LOGDAO();
        if ($stmt->execute()) {
            $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logdao->logPreparedStatement('INSERT', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to add event (' . $userID . ')');
            return false;
        }
    }

    public function deleteEvent($eventID){
        $sql = "DELETE FROM events WHERE eventID = $eventID;";
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':eventID', $eventID);
        $binds = array(
            ":eventID" => $eventID
        );
        $logdao = new LOGDAO();
        if ($stmt->execute()) {
            $logdao->logPreparedStatement('DELETE', $stmt, $binds, 'SUCCESS');
            return true;
        } else {
            $logdao->logPreparedStatement('DELETE', $stmt, $binds, 'FAILED');
            throw new Exception('Failed to delete event (' . $eventID . ')');
            return false;
        }
    }

    /**
     * This method creates an array of Event objects given results from sql queries
     * @param $eventResults
     * @param $dropResults
     * @param $slotResults
     * @return Event array
     */
    private function createEventArray($eventResults, $dropResults, $slotResults)
    {
        $result = array();
        $dropPointer = 0;
        $slotPointer = 0;
        foreach ($eventResults as $row) {
            $event = EVENT::create($row["eventID"], $row["eventType"], $row["startDate"], $row["completeDate"],
                $row["eventState"], $row["recurringEvent"], $row["dayOfWeek"], $row["hourOfDay"], $row["eventName"]);

            $dropList = array();
            while ($dropResults[$dropPointer]["eventID"] == $row["eventID"]) {
                $drop = DROP::create($dropResults[$dropPointer]["eventID"], $dropResults[$dropPointer]["dropID"],
                    $dropResults[$dropPointer]["holdingUserID"], $dropResults[$dropPointer]["sold"],
                    $dropResults[$dropPointer]["soldPrice"], $dropResults[$dropPointer]["itemID"]);
                $drop->setItemName($dropResults[$dropPointer]["name"]);
                $drop->setAegisName($dropResults[$dropPointer]["aegisName"]);
                array_push($dropList, $drop);
                $dropPointer++;
            }
            $event->setDropList($dropList);

            $slotList = array();
            while ($slotResults[$slotPointer]["eventID"] == $row["eventID"]) {
                $slot = SLOT::create($slotResults[$slotPointer]["eventID"], $slotResults[$slotPointer]["slotID"],
                    $slotResults[$slotPointer]["slotClass"], $slotResults[$slotPointer]["taken"],
                    $slotResults[$slotPointer]["takenUserID"], $slotResults[$slotPointer]["takenCharID"]);
                $slot->setUserLogin($slotResults[$slotPointer]["userLogin"]);
                $slot->setCharClass($slotResults[$slotPointer]["charClass"]);
                $slot->setCharName($slotResults[$slotPointer]["charName"]);
                array_push($slotList, $slot);
                $slotPointer++;
            }
            $event->setSlotList($slotList);

            array_push($result, $event);
        }
        return $result;
    }
}
?>