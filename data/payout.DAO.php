<?php
require_once("../data/dbconfig.DAO.php");
require_once("../data/log.DAO.php");
require_once("../class/events.class.php");

class PAYOUTDAO
{
    public function getAllPayoutsFromEvents($eventList)
    {
        $userList = array();
        foreach ($eventList as $event) {
            $eventTotalWorth = $event->getTotalValue();
            $totalParticipants = $event->getTotalParticipants() + 1; //+1 = guild bank share.
            foreach ($event->getParticipants() as $participant) {
                $paidOut = $participant->getPaidOut();
                $toBePaid = floor($eventTotalWorth / $totalParticipants) - $paidOut;
                if (!isset($userList[$participant->getUserID()])) {
                    $array = array();
                    $userList[$participant->getUserID()][0] = $participant;
                    $userList[$participant->getUserID()][1] = $toBePaid;
                } else {
                    $userList[$participant->getUserID()][1] += $toBePaid;
                }

            }
        }
        return $userList;
    }

    public function payOutUser($id, $eventList)
    {
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $logdao = new LogDAO();
        foreach ($eventList as $event) {
            if ($event->getTotalValue() > 0) {
                $eventTotalWorth = $event->getTotalValue();
                $totalParticipants = $event->getTotalParticipants() + 1; //+1 = guild bank share.
                $paidOut = floor($eventTotalWorth / $totalParticipants);
                $alreadyPaidOut = $event->getParticipants()[$id]->getPaidOut();
                if ($paidOut <= $alreadyPaidOut) {
                    continue; //no need to update runs that are up to date already.
                }
                $runID = $event->getID();
                $sql = "UPDATE participants SET  `paidOut` = :paidout WHERE `participants`.`runID` = :runID AND `participants`.`userID` = :id;";
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':paidout', $paidOut);
                $stmt->bindParam(':runID', $runID);
                $stmt->bindParam(':id', $id);
                $binds = array(
                    ":paidout" => $paidOut,
                    ":runID" => $runID,
                    ":id" => $id,
                );
                if ($stmt->execute()) { //1 if success, 0 if fail
                    $logdao->logPreparedStatement('UPDATE', $stmt, $binds, 'SUCCESS');
                } else {
                    $logdao->logPreparedStatement('UPDATE', $stmt, $binds, 'FAILED');
                    throw new Exception('Could not pay out somehow.');
                    return false;
                }
            }
        }
    }
}

?>