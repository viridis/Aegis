<?php
require_once("../data/forumadminconfig.DAO.php");

class ForumUser{
    private $name;
    private $password;
    private $session;

    public function __construct(){
        $this->name = ForumAdminConfig::$USERNAME;
        $this->password = ForumAdminConfig::$PASSWORD;
    }

    public function logIn(){
        $url = 'http://z13.invisionfree.com/Aegis_Forums/admin.php?adsess=';
        $postData = array(
            'username' => $this->name,
            'password' => $this->password,
            'adsess' => '',
            'login' => 'yes',
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_exec($ch);
        $curlInfo = curl_getinfo($ch);
        curl_close($ch);
        $this->saveSession($curlInfo);
    }

    public function userExists($userName){
        $page = $this->searchUser($userName);
        if(!$this->pageContainsUser($page, $userName)){
            return false;
        }
        if(!$this->isUserAllowedAccess($page)){
            return false;
        }
        return true;
    }

    private function searchUser($userName){
        $url = 'http://z13.invisionfree.com/Aegis_Forums/admin.php?adsess='. $this->session;
        $postData = array(
            'USER_NAME' => $userName,
            'adsess' => $this->session,
            'code' => 'stepone',
            'act' => 'mem',
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $html = curl_exec($ch);
        curl_close($ch);
        return $html;
    }

    private function pageContainsUser($page, $userName){
        $pattern = "/target='blank'>". $userName ."<\/a>/i";
        return preg_match($pattern, $page);
    }

    private function isUserAllowedAccess($page){
        $pattern = "/<td class='tdrow2'  width='10%'  valign='middle'>(.*)<\/td>/i";
        if(preg_match($pattern, $page, $matches)){
            return $this->isALegalGroup($matches[1]);
        }
        return false;
    }

    private function isALegalGroup($groupName){
        $illegalMemberGroups = array('Validating', 'Guest', 'Disabled/Banned');
        if(in_array($groupName, $illegalMemberGroups)){
            return false;
        }
        return true;
    }

    private function saveSession($curlInfo){
        $session = $this->extractSession($curlInfo['url']);
        $this->session = $session;
    }

    private function extractSession($curlInfo){
        $info = explode('=', $curlInfo);
        return $info[1];
    }
}