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
require_once("../service/charClass.service.php");
require_once("../service/slotClass.service.php");

class DataService
{
    public function getAllEventInfo()
    {
        $eventService = new EventService();
        $eventResults = $eventService->getAllEvents();
        $eventArray = $this->createEventArray($eventResults);
        $dropService = new DropService();
        $dropResults = $dropService->getAllDrops();
        $dropArray = $this->createDropArray($dropResults);
        $slotService = new SlotService();
        $slotResults = $slotService->getAllSlots();
        $slotArray = $this->createSlotArray($slotResults);
        $completeEvents = $this->createCompleteEventArray($eventArray, $dropArray, $slotArray);
        return $completeEvents;
    }

    public function getEventByEventID($eventID)
    {
        $eventService = new EventService();
        $eventResults = $eventService->getEventByEventID($eventID);
        $eventArray = $this->createEventArray($eventResults);
        $dropService = new DropService();
        $dropResults = $dropService->getDropByEventID($eventID);
        $dropArray = $this->createDropArray($dropResults);
        $slotService = new SlotService();
        $slotResults = $slotService->getSlotByEventID($eventID);
        $slotArray = $this->createSlotArray($slotResults);
        $completeEvents = $this->createCompleteEventArray($eventArray, $dropArray, $slotArray);
        return current($completeEvents);
    }

    public function getEventByAttribute($attribute, $attributeValue)
    {
        $eventService = new EventService();
        $eventResults = $eventService->getEventByAttribute($attribute, $attributeValue);
        $eventArray = $this->createEventArray($eventResults);
        $eventIDArray = array();
        foreach ($eventArray as $event) {
            /** @var Event $event */
            array_push($eventIDArray, $event->getEventID());
        }
        $dropService = new DropService();
        $dropResults = $dropService->getDropByAttribute("eventID", $eventIDArray);
        $dropArray = $this->createDropArray($dropResults);
        $slotService = new SlotService();
        $slotResults = $slotService->getSlotByAttribute("eventID", $eventIDArray);
        $slotArray = $this->createSlotArray($slotResults);
        $completeEvents = $this->createCompleteEventArray($eventArray, $dropArray, $slotArray);
        return $completeEvents;
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
        return current($completeEventTypes);
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
        $userResults = $userService->getAllUsers();
        $userArray = $this->createUserArray($userResults);
        $gameAccountService = new GameAccountService();
        $gameAccountResults = $gameAccountService->getAllGameAccounts();
        $gameAccountArray = $this->createGameAccountArray($gameAccountResults);
        $characterService = new CharacterService();
        $characterResults = $characterService->getAllCharacters();
        $characterArray = $this->createCharacterArray($characterResults);
        $cooldownService = new CooldownService();
        $cooldownResults = $cooldownService->getAllCooldowns();
        $cooldownArray = $this->createCooldownArray($cooldownResults);
        $completeUsers = $this->createCompleteUserArray($userArray, $gameAccountArray, $characterArray, $cooldownArray);
        return $completeUsers;
    }

    public function getUserByUserID($userID)
    {
        $userService = new UserService();
        $userResults = $userService->getUserByUserID($userID);
        $userArray = $this->createUserArray($userResults);
        $gameAccountService = new GameAccountService();
        $gameAccountResults = $gameAccountService->getGameAccountByUserID($userID);
        $gameAccountArray = $this->createGameAccountArray($gameAccountResults);
        $characterService = new CharacterService();
        $characterResults = $characterService->getCharactersByUserID($userID);
        $characterArray = $this->createCharacterArray($characterResults);
        $cooldownService = new CooldownService();
        $cooldownResults = $cooldownService->getCooldownsByUserID($userID);
        $cooldownArray = $this->createCooldownArray($cooldownResults);
        $completeUsers = $this->createCompleteUserArray($userArray, $gameAccountArray, $characterArray, $cooldownArray);
        return current($completeUsers);
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
        $gameAccountResults = $gameAccountService->getGameAccountByAccountID($accountID);
        $gameAccountArray = $this->createGameAccountArray($gameAccountResults);
        $characterService = new CharacterService();
        $characterResults = $characterService->getCharactersByAccountID($accountID);
        $characterArray = $this->createCharacterArray($characterResults);
        $cooldownService = new CooldownService();
        $cooldownResults = $cooldownService->getCooldownsByAccountID($accountID);
        $cooldownArray = $this->createCooldownArray($cooldownResults);
        $completeGameAccounts = $this->createCompleteGameAccountArray($gameAccountArray, $characterArray, $cooldownArray);
        return current($completeGameAccounts);
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
        return current($completeCooldowns);
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
        $cooldownResults = $cooldownService->getCooldownByEvent($event);
        $completeCooldowns = $this->createCooldownArray($cooldownResults);
        return $completeCooldowns;
    }

    public function getAllCharClasses()
    {
        $charClassService = new charClassService();
        $charClassResults = $charClassService->getAllCharClasses();
        $completeCharClass = $this->createCharClassArray($charClassResults);
        return $completeCharClass;
    }

    public function getAllSlotClasses()
    {
        $slotClassService = new SlotClassService();
        $slotClassResults = $slotClassService->getAllSlotClasses();
        $slotClassRulesResults = $slotClassService->getAllSlotClassRules();
        $completeSlotClasses = $this->createSlotClassArray($slotClassResults, $slotClassRulesResults);
        return $completeSlotClasses;
    }

    private function createSlotClassArray($slotClassResults, $slotClassRulesResults)
    {
        $result = array();
        foreach ($slotClassResults as $row) {
            $slotClass = new SlotClass($row["slotClassID"], $row["slotClassName"]);
            $result[$row["slotClassID"]] = $slotClass;
        }

        foreach ($slotClassRulesResults as $row) {
            /** @var SlotClass $slotClass */
            $slotClass = $result[$row["slotClassID"]];
            $allowedCharClassArray = $slotClass->getAllowedCharClassArray();
            $allowedCharClassArray[$row["charClassID"]] = $row["charClassID"];
            $slotClass->setAllowedCharClassArray($allowedCharClassArray);
        }
        return $result;
    }

    private function createCharClassArray($charClassResults)
    {
        $result = array();
        foreach ($charClassResults as $row) {
            $charClass = new CharClass($row["charClassID"], $row["charClassName"]);
            $result[$row["charClassID"]] = $charClass;
        }
        return $result;
    }

    private function createCompleteUserArray($userAccountArray, $gameAccountArray, $characterArray, $cooldownArray)
    {
        $completeGameAccountArray = $this->createCompleteGameAccountArray($gameAccountArray, $characterArray, $cooldownArray);
        foreach ($completeGameAccountArray as $gameAccount) {
            /** @var GameAccount $gameAccount */
            $userID = $gameAccount->getUserID();
            /** @var User $user */
            $user = $userAccountArray[$userID];

            $gameAccountContainer = $user->getGameAccountContainer();
            $gameAccountContainer[$gameAccount->getAccountID()] = $gameAccount;
            $user->setGameAccountContainer($gameAccountContainer);
        }
        return $userAccountArray;

    }

    private function createCompleteEventArray($eventArray, $dropArray, $slotArray)
    {
        foreach ($dropArray as $drop) {
            /** @var Drop $drop */
            $eventID = $drop->getEventID();
            /** @var Event $event */
            $event = $eventArray[$eventID];
            $dropContainer = $event->getDropList();
            $dropContainer[$drop->getDropID()] = $drop;
            $event->setDropList($dropContainer);
        }
        foreach ($slotArray as $slot) {
            /** @var Slot $slot */
            $eventID = $slot->getEventID();
            /** @var Event $event */
            $event = $eventArray[$eventID];
            $slotContainer = $event->getSlotList();
            $slotContainer[$slot->getSlotID()] = $slot;
            $event->setSlotList($slotContainer);
        }
        return $eventArray;
    }

    private function createCompleteGameAccountArray($gameAccountArray, $characterArray, $cooldownArray)
    {
        foreach ($cooldownArray as $cooldown) {
            /** @var Cooldown $cooldown */
            if ($cooldown->getCooldownType() == 1) {
                $gameAccountID = $cooldown->getAccountID();
                /** @var GameAccount $gameAccount */
                $gameAccount = $gameAccountArray[$gameAccountID];
                $cooldownContainer = $gameAccount->getCooldownContainer();
                $cooldownContainer[$cooldown->getCooldownID()] = $cooldown;
                $gameAccount->setCooldownContainer($cooldownContainer);
            } else if ($cooldown->getCooldownType() == 2) {
                $charID = $cooldown->getCharID();
                /** @var Character $character */
                $character = $characterArray[$charID];
                $cooldownContainer = $character->getCooldownContainer();
                $cooldownContainer[$cooldown->getCooldownID()] = $cooldown;
                $character->setCooldownContainer($cooldownContainer);
            }
        }

        foreach ($characterArray as $character) {
            /** @var Character $character */
            $gameAccountID = $character->getAccountID();
            $gameAccount = $gameAccountArray[$gameAccountID];
            $characterContainer = $gameAccount->getCharacterList();
            $characterContainer[$character->getCharID()] = $character;
            $gameAccount->setCharacterList($characterContainer);
        }
        return $gameAccountArray;
    }

    private function createEventArray($eventResults)
    {
        $result = array();
        foreach ($eventResults as $row) {
            /** @var Event $event */
            $event = Event::create($row["eventID"], $row["eventTypeID"], $row["startDate"], $row["completeDate"],
                $row["eventState"], $row["recurringEvent"], $row["dayOfWeek"], $row["hourOfDay"], $row["eventName"]);
            $event->setAccountCooldown($row["accountCooldown"]);
            $event->setCharacterCooldown($row["characterCooldown"]);
            $event->setDropList(array());
            $event->setSlotList(array());
            $eventName = $row["eventName"];
            if ($event->isRecurringEvent()) {
                $eventName = "[Weekly] " . $eventName;
            }
            $event->setEventName($eventName);
            $result[$row["eventID"]] = $event;
        }
        return $result;
    }

    private function createUserArray($userAccountResults)
    {
        $result = array();
        foreach ($userAccountResults as $row) {
            /** @var User $user */
            $user = User::create($row['userID'], $row['userLogin'], $row['email'], $row['mailChar'],
                $row['userPassword'], $row['roleLevel'], $row['forumAccount'], $row['payout']);
            $user->setGameAccountContainer(array());
            $result[$row["userID"]] = $user;
        }
        return $result;
    }

    private function createGameAccountArray($gameAccountResults)
    {
        $result = array();
        foreach ($gameAccountResults as $row) {
            /** @var GameAccount $gameAccount */
            $gameAccount = GameAccount::create($row["userID"], $row["accountID"], $row["gameAccountName"]);
            $gameAccount->setCharacterList(array());
            $result[$row["accountID"]] = $gameAccount;
        }
        return $result;
    }

    private function createCharacterArray($characterResults)
    {
        $result = array();
        foreach ($characterResults as $row) {
            /** @var Character $character */
            $character = Character::create($row["accountID"], $row["charID"], $row["charName"],
                $row["charClassID"], $row["userID"]);
            $character->setCharClassName($row["charClassName"]);
            $result[$row["charID"]] = $character;
        }
        return $result;
    }

    private function createEventTypeArray($eventTypeResults)
    {
        $result = array();
        foreach ($eventTypeResults as $row) {
            $eventType = EventType::create($row["eventTypeID"], $row["eventName"], $row["characterCooldown"],
                $row["accountCooldown"], $row["numSlots"]);
            $result[$row["eventTypeID"]] = $eventType;
        }
        return $result;
    }

    private function createCooldownArray($cooldownResults)
    {
        $result = array();
        foreach ($cooldownResults as $row) {
            $cooldown = Cooldown::create($row["cooldownID"], $row["eventID"], $row["accountID"], $row["charID"],
                $row["endDate"], $row["eventTypeID"], $row["cooldownType"], $row["userID"]);
            $result[$row["cooldownID"]] = $cooldown;
        }
        return $result;
    }

    private function createDropArray($dropResults)
    {
        $result = array();
        foreach ($dropResults as $row) {
            /** @var Drop $drop */
            $drop = Drop::create($row["eventID"], $row["dropID"],
                $row["holdingUserID"], $row["sold"],
                $row["soldPrice"], $row["itemID"]);
            $drop->setItemName($row["name"]);
            $drop->setAegisName($row["aegisName"]);
            $result[$row["dropID"]] = $drop;
        }
        return $result;
    }

    private function createSlotArray($slotResults)
    {
        $result = array();
        foreach ($slotResults as $row) {
            /** @var Slot $slot */
            $slot = Slot::create($row["eventID"], $row["slotID"],
                $row["slotClassID"], $row["taken"],
                $row["takenUserID"], $row["takenCharID"]);
            $slot->setUserLogin($row["userLogin"]);
            $slot->setCharClassID($row["charClassID"]);
            $slot->setCharName($row["charName"]);
            $slot->setAccountID($row["accountID"]);
            $slot->setSlotClassName($row["slotClassName"]);
            $result[$row["slotID"]] = $slot;
        }
        return $result;
    }
}