<?php
require_once("../service/data.service.php");

class PayoutService
{
    public function payoutUser($user)
    {
        $dataService = new DataService();
        $dataService->setUserPayout($user, 0);
    }
}