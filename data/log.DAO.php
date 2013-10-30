<?php
require_once("../data/dbconfig.DAO.php");

class LOGDAO{
    public function logEntry($action, $query, $result){
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $query = $dbh->quote($query);
        $sql = "INSERT INTO logs (`action`, `query`, `result`, `timestamp`) VALUES ('". $action ."', \"". $query ."\", '". $result ."', NOW());";
        if($dbh->exec($sql)){  //1 if success, 0 if fail
            return true;
        }
        $email = DBConfig::$DB_ADMIN_EMAIL;
        $subject = 'Database Logging Error';
        $message = '\n Following Query caused an error:
                    \n '. $sql .'
                    \n PDO ErrorCode:
                    \n '. $dbh->errorCode();
        mail($email, $subject, $message);
        return false;
    }
}
?>