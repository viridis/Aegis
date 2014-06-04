<?php
require_once("../service/user.service.php");
require_once("../service/gameAccount.service.php");
require_once("../service/character.service.php");
require_once("../service/event.service.php");
require_once("../service/drop.service.php");
require_once("../service/slot.service.php");
require_once("../service/item.service.php");
require_once("../service/eventType.service.php");
require_once("../service/cooldown.service.php");

class DataService
{
    public function getAllEventInfo()
    {
        $eventService = new EventService();
        $incompleteEvents = $eventService->getAllEvents();
        $dropService = new DropService();
        $completeDrops = $dropService->getAllDrops();
        $slotService = new SlotService();
        $completeSlots = $slotService->getAllSlots();
        $completeEvents = $this->createEventArray($incompleteEvents, $completeDrops, $completeSlots);
        return $completeEvents;
    }

    public function getEventByEventID($eventID)
    {
        $eventService = new EventService();
        $incompleteEvents = $eventService->getEventByEventID($eventID);
        $dropService = new DropService();
        $completeDrops = $dropService->getDropByEventID($eventID);
        $slotService = new SlotService();
        $completeSlots = $slotService->getSlotByEventID($eventID);
        $completeEvents = $this->createEventArray($incompleteEvents, $completeDrops, $completeSlots);
        return $completeEvents[0];
    }

    public function createEvent($event)
    {
        $eventService = new EventService();
        return $eventService->createEvent($event);
    }

    public function setEventName($event, $eventName)
    {
        /** @var Event $event */
        $event->setEventName($eventName);
        return $this->updateEvent($event);
    }

    public function updateEvent($event)
    {
        $eventService = new EventService();
        return $eventService->updateEvent($event);
    }

    public function deleteEvent($event)
    {
        $eventService = new EventService();
        return $eventService->deleteEvent($event);
    }

    public function getAllEventTypes()
    {
        $eventTypeService = new EventTypeService();
        $eventTypeResults = $eventTypeService->getAllEventTypes();
        $completeEventTypes = $this->createEventTypeArray($eventTypeResults);
        return $completeEventTypes;
    }

    public function getEventTypeByEventTypeID($eventTypeID)
    {
        $eventTypeService = new EventTypeService();
        $eventTypeResults = $eventTypeService->getEventTypeByEventTypeID($eventTypeID);
        $completeEventTypes = $this->createEventTypeArray($eventTypeResults);
        if (isset($completeEventTypes[0])) {
            return $completeEventTypes[0];
        }
        return false;
    }

    public function updateEventType($eventType)
    {
        $eventTypeService = new EventTypeService();
        return $eventTypeService->updateEventType($eventType);
    }

    public function createEventType($eventType)
    {
        $eventTypeService = new EventTypeService();
        return $eventTypeService->createEventType($eventType);
    }

    public function deleteEventType($eventType)
    {
        $eventTypeService = new EventTypeService();
        return $eventTypeService->deleteEventType($eventType);
    }

    public function addSlotToEvent($event, $slotClassID)
    {
        /** @var Event $event */
        $slot = new Slot($event->getEventID(), NULL, $slotClassID, NULL, NULL, NULL);
        $slotService = new SlotService();
        return $slotService->createSlot($slot);
    }

    public function setSlotClassID($slot, $slotClassID)
    {
        /** @var Slot $slot */
        $slot->setSlotClassID($slotClassID);
        return $this->updateSlot($slot);
    }

    public function updateSlot($slot)
    {
        $slotService = new SlotService();
        return $slotService->updateSlot($slot);
    }

    public function deleteSlot($slot)
    {
        $slotService = new SlotService();
        return $slotService->deleteSlot($slot);
    }

    public function addDropToEvent($event, $item)
    {
        /** @var Event $event */
        /** @var Item $item */
        $drop = new Drop($event->getEventID(), NULL, NULL, NULL, NULL, $item->getItemID());
        $dropService = new DropService();
        return $dropService->createDrop($drop);
    }

    public function sellDrop($drop, $soldPrice)
    {
        /** @var Drop $drop */
        if (!$drop->isSold()) {
            $drop->setSold(true);
            $drop->setSoldPrice($soldPrice);
            return $this->updateDrop($drop);
        }
        return false;
    }

    public function updateDrop($drop)
    {
        $dropService = new DropService();
        return $dropService->updateDrop($drop);
    }

    public function removeDrop($drop)
    {
        $dropService = new DropService();
        return $dropService->removeDrop($drop);
    }

    public function getItemByItemID($itemID)
    {
        $itemDAO = new ItemDAO();
        return $itemDAO->getItemByID($itemID);
    }

    public function listAllItems()
    {
        $itemDAO = new ItemDAO();
        return $itemDAO->getAllItems();
    }

    public function getAllUserInfo()
    {
        $userService = new UserService();
        $incompleteUsers = $userService->getAllUsers();
        $gameAccountService = new GameAccountService();
        $incompleteGameAccounts = $gameAccountService->getAllGameAccounts();
        $characterService = new CharacterService();
        $completeCharacters = $characterService->getAllCharacters();
        $completeUsers = $this->createUserArray($incompleteUsers, $incompleteGameAccounts, $completeCharacters);
        return $completeUsers;
    }

    public function getUserByUserID($userID)
    {
        $userService = new UserService();
        $incompleteUsers = $userService->getUserByUserID($userID);
        $gameAccountService = new GameAccountService();
        $incompleteGameAccounts = $gameAccountService->getGameAccountByUserID($userID);
        $characterService = new CharacterService();
        $completeCharacters = $characterService->getCharactersByUserID($userID);
        $completeUsers = $this->createUserArray($incompleteUsers, $incompleteGameAccounts, $completeCharacters);
        if (isset($completeUsers[0])) {
            return $completeUsers[0];
        }
        return false;
    }

    public function getUserByLoginAndPassword($userLogin, $userPassword)
    {
        $userService = new UserService();
        $userID = $userService->getUserIDByLoginAndPassword($userLogin, $userPassword);
        $user = $this->getUserByUserID($userID);
        return $user;
    }

    public function setUserPayout($user, $payout)
    {
        /** @var User $user */
        $user->setPayout($payout);
        return $this->updateUser($user);
    }

    public function setUserMailChar($user, $mailChar)
    {
        /** @var User $user */
        $user->setMailChar($mailChar);
        return $this->updateUser($user);
    }

    public function setUserPassword($user, $userPassword)
    {
        /** @var User $user */
        $user->setUserPassword($userPassword);
        return $this->updateUser($user);
    }

    public function updateUser($user)
    {
        $userService = new UserService();
        return $userService->updateUser($user);
    }

    public function createUser($user)
    {
        $userService = new UserService();
        return $userService->createUser($user);
    }

    public function createGameAccount($gameAccount)
    {
        $gameAccountService = new GameAccountService();
        return $gameAccountService->createGameAccount($gameAccount);
    }

    public function getGameAccountByAccountID($accountID)
    {
        $gameAccountService = new GameAccountService();
        $incompleteGameAccounts = $gameAccountService->getGameAccountByAccountID($accountID);
        $characterService = new CharacterService();
        $completeCharacters = $characterService->getCharactersByAccountID($accountID);
        $cooldownService = new CooldownService();
        $completeCooldowns = $cooldownService->getCooldownsByAccountID($accountID);
        $completeGameAccounts = $this->createGameAccountArray($incompleteGameAccounts, $completeCharacters);
        return $completeGameAccounts[0];
    }

    public function updateGameAccount($gameAccount)
    {
        $gameAccountService = new GameAccountService();
        return $gameAccountService->updateGameAccount($gameAccount);
    }

    public function deleteGameAccount($gameAccount)
    {
        $gameAccountService = new GameAccountService();
        return $gameAccountService->deleteGameAccount($gameAccount);
    }

    public function createCharacter($character)
    {
        $characterService = new CharacterService();
        return $characterService->createCharacter($character);
    }

    public function updateCharacter($character)
    {
        $characterService = new CharacterService();
        return $characterService->updateCharacter($character);
    }

    public function deleteCharacter($character)
    {
        $characterService = new CharacterService();
        return $characterService->deleteCharacter($character);
    }

    public function getAllCooldowns()
    {
        $cooldownService = new CooldownService();
        $cooldownResults = $cooldownService->getAllCooldowns();
        $completeCooldowns = $this->createCooldownArray($cooldownResults);
        return $completeCooldowns;
    }

    public function getCooldownByCooldownID($cooldownID)
    {
        $cooldownService = new CooldownService();
        $cooldownResults = $cooldownService->getCooldownByCooldownID($cooldownID);
        $completeCooldowns = $this->createCooldownArray($cooldownResults);
        if (isset($completeCooldowns[0])) {
            return $completeCooldowns[0];
        }
        return false;
    }

    public function createCooldown($cooldown)
    {
        $cooldownService = new CooldownService();
        return $cooldownService->createCooldown($cooldown);
    }

    public function updateCooldown($cooldown)
    {
        $cooldownService = new CooldownService();
        return $cooldownService->updateCooldown($cooldown);
    }

    public function deleteCooldown($cooldown)
    {
        $cooldownService = new CooldownService();
        return $cooldownService->deleteCooldown($cooldown);
    }

    public function getCooldownByEvent($event)
    {
        $cooldownService = new CooldownService();
        $cooldownResults = $cooldownService->getCooldownsByEvent($event);
        $completeCooldowns = $this->createCooldownArray($cooldownResults);
        return $completeCooldowns;
    }

    private function createUserArray($userAccountResults, $gameAccountResults, $characterResults)
    {
        $result = array();
        $gameAccountPointer = 0;
        $characterPointer = 0;
        foreach ($userAccountResults as $row) {
            /** @var User $user */
            $user = User::create($row['userID'], $row['userLogin'], $row['email'], $row['mailChar'],
                $row['userPassword'], $row['roleLevel'], $row['forumAccount'], $row['payout']);
            $gameAccountList = array();
            while (isset($gameAccountResults[$gameAccountPointer]) && $gameAccountResults[$gameAccountPointer]["userID"] <= $row["userID"]) {
                /** @var GameAccount $gameAccount */
                $gameAccount = GameAccount::create($gameAccountResults[$gameAccountPointer]["userID"],
                    $gameAccountResults[$gameAccountPointer]["accountID"], $gameAccountResults[$gameAccountPointer]["cooldown"], $gameAccountResults[$gameAccountPointer]["gameAccountName"]);
                $characterList = array();
                while (isset($characterResults[$characterPointer]["accountID"]) && $characterResults[$characterPointer]["accountID"] == $gameAccountResults[$gameAccountPointer]["accountID"]) {
                    $character = Character::create($characterResults[$characterPointer]["accountID"],
                        $characterResults[$characterPointer]["charID"], $characterResults[$characterPointer]["charName"],
                        $characterResults[$characterPointer]["cooldown"], $characterResults[$characterPointer]["charClassID"],
                        $characterResults[$characterPointer]["userID"]);
                    array_push($characterList, $character);
                    $characterPointer++;
                }
                $gameAccount->setCharacterList($characterList);
                array_push($gameAccountList, $gameAccount);
                $gameAccountPointer++;
            }
            $user->setGameAccountContainer($gameAccountList);
            array_push($result, $user);
        }
        return $result;
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
            /** @var Event $event */
            $event = Event::create($row["eventID"], $row["eventTypeID"], $row["startDate"], $row["completeDate"],
                $row["eventState"], $row["recurringEvent"], $row["dayOfWeek"], $row["hourOfDay"], $row["eventName"]);
            $event->setAccountCooldown($row["accountCooldown"]);
            $event->setCharacterCooldown($row["characterCooldown"]);
            $eventName = $row["eventName"];
            if ($event->isRecurringEvent()) {
                $eventName = "[Weekly] " . $eventName;
            }
            $event->setEventName($eventName);
            $dropList = array();
            while (isset($dropResults[$dropPointer]["eventID"]) && $dropResults[$dropPointer]["eventID"] == $row["eventID"]) {
                /** @var Drop $drop */
                $drop = Drop::create($dropResults[$dropPointer]["eventID"], $dropResults[$dropPointer]["dropID"],
                    $dropResults[$dropPointer]["holdingUserID"], $dropResults[$dropPointer]["sold"],
                    $dropResults[$dropPointer]["soldPrice"], $dropResults[$dropPointer]["itemID"]);
                $drop->setItemName($dropResults[$dropPointer]["name"]);
                $drop->setAegisName($dropResults[$dropPointer]["aegisName"]);
                array_push($dropList, $drop);
                $dropPointer++;
            }
            $event->setDropList($dropList);

            $slotList = array();
            while (isset($slotResults[$slotPointer]["eventID"]) && $slotResults[$slotPointer]["eventID"] == $row["eventID"]) {
                /** @var Slot $slot */
                $slot = Slot::create($slotResults[$slotPointer]["eventID"], $slotResults[$slotPointer]["slotID"],
                    $slotResults[$slotPointer]["slotClassID"], $slotResults[$slotPointer]["taken"],
                    $slotResults[$slotPointer]["takenUserID"], $slotResults[$slotPointer]["takenCharID"]);
                $slot->setUserLogin($slotResults[$slotPointer]["userLogin"]);
                $slot->setCharClassID($slotResults[$slotPointer]["charClassID"]);
                $slot->setCharName($slotResults[$slotPointer]["charName"]);
                $slot->setAccountID($slotResults[$slotPointer]["accountID"]);
                array_push($slotList, $slot);
                $slotPointer++;
            }
            $event->setSlotList($slotList);
            array_push($result, $event);
        }
        return $result;
    }

    private function createGameAccountArray($gameAccountResults, $characterResults)
    {
        $result = array();
        $characterPointer = 0;
        foreach ($gameAccountResults as $row) {
            /** @var GameAccount $gameAccount */
            $gameAccount = GameAccount::create($row["userID"], $row["accountID"], $row["gameAccountName"]);
            $characterList = array();
            while (isset($characterResults[$characterPointer]) && $characterResults[$characterPointer]["accountID"] <= $row["accountID"]) {
                $character = Character::create($characterResults[$characterPointer]["accountID"],
                    $characterResults[$characterPointer]["charID"], $characterResults[$characterPointer]["charName"],
                    $characterResults[$characterPointer]["cooldown"], $characterResults[$characterPointer]["charClassID"],
                    $characterResults[$characterPointer]["userID"]);
                array_push($characterList, $character);
                $characterPointer++;
            }
            $gameAccount->setCharacterList($characterList);
            array_push($result, $gameAccount);
        }
        return $result;
    }

    private function createEventTypeArray($eventTypeResults)
    {
        $result = array();
        foreach ($eventTypeResults as $row) {
            $eventType = EventType::create($row["eventTypeID"], $row["eventName"], $row["characterCooldown"],
                $row["accountCooldown"], $row["numSlots"]);
            array_push($result, $eventType);
        }
        return $result;
    }

    private function createCooldownArray($cooldownResults)
    {
        $result = array();
        foreach ($cooldownResults as $row) {
            $cooldown = Cooldown::create($row["cooldownID"], $row["eventID"], $row["accountID"], $row["charID"],
                $row["endDate"], $row["eventTypeID"]);
            array_push($result, $cooldown);
        }
        return $result;
    }
}