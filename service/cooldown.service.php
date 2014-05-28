<?php
require_once("../data/cooldown.DAO.php");

class CooldownService
{
    public function getAllCooldowns()
    {
        $cooldownDAO = new CooldownDAO();
        return $cooldownDAO->getAllCooldowns();
    }

    public function getCooldownByCooldownID($cooldownID)
    {
        $cooldownDAO = new CooldownDAO();
        return $cooldownDAO->getCooldownByCooldownID($cooldownID);
    }

    public function createCooldown($cooldown)
    {
        $cooldownDAO = new CooldownDAO();
        return $cooldownDAO->createCooldown($cooldown);
    }

    public function deleteCooldown($cooldown)
    {
        /** @var Cooldown $cooldown */
        $cooldownDAO = new CooldownDAO();
        return $cooldownDAO->deleteCooldown($cooldown->getCooldownID());
    }

    public function updateCooldown($cooldown)
    {
        $cooldownDAO = new CooldownDAO();
        return $cooldownDAO->updateCooldown($cooldown);
    }

    public function getCooldownsByEvent($event)
    {
        /** @var Event $event */
        $cooldownDAO = new CooldownDAO();
        return $cooldownDAO->getAllCooldownsByEventID($event->getEventID());
    }
}