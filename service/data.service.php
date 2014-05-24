<?php
require_once("../service/user.service.php");
require_once("../service/gameAccount.service.php");
require_once("../service/character.service.php");
require_once("../service/event.service.php");
require_once("../service/drop.service.php");
require_once("../service/slot.service.php");
require_once("../service/item.service.php");

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
        $eventService->createEvent($event);
    }

    public function setEventName($event, $eventName)
    {
        /** @var Event $event */
        $event->setEventName($eventName);
        $this->updateEvent($event);
    }

    public function updateEvent($event)
    {
        $eventService = new EventService();
        $eventService->updateEvent($event);
    }

    public function deleteEvent($event)
    {
        $eventService = new EventService();
        $eventService->deleteEvent($event);
    }

    public function addSlotToEvent($event, $slotClass)
    {
        /** @var Event $event */
        $slot = new Slot($event->getEventID(), NULL, $slotClass, NULL, NULL, NULL);
        $slotService = new SlotService();
        $slotService->createSlot($slot);
    }

    public function setSlotClass($slot, $slotClass)
    {
        /** @var Slot $slot */
        $slot->setSlotClass($slotClass);
        $this->updateSlot($slot);
    }

    public function updateSlot($slot)
    {
        $slotService = new SlotService();
        $slotService->updateSlot($slot);
    }

    public function deleteSlot($slot)
    {
        $slotService = new SlotService();
        $slotService->deleteSlot($slot);
    }

    public function addDropToEvent($event, $item)
    {
        /** @var Event $event */
        /** @var Item $item */
        $drop = new Drop($event->getEventID(), NULL, NULL, NULL, NULL, $item->getItemID());
        $dropService = new DropService();
        $dropService->createDrop($drop);
    }

    public function sellDrop($drop, $soldPrice)
    {
        /** @var Drop $drop */
        if (!$drop->isSold())
        {
            $drop->setSold(true);
            $drop->setSoldPrice($soldPrice);
            $this->updateDrop($drop);
        }
    }

    public function updateDrop($drop)
    {
        $dropService = new DropService();
        $dropService->updateDrop($drop);
    }

    public function removeDrop($drop)
    {
        $dropService = new DropService();
        $dropService->removeDrop($drop);
    }

    public function getItemByItemID($itemID)
    {
        $itemDAO = new ItemDAO();
        return $itemDAO->getItemByID($itemID);
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
        return $completeUsers[0];
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
        $this->updateUser($user);
    }

    public function setUserMailChar($user, $mailChar)
    {
        /** @var User $user */
        $user->setMailChar($mailChar);
        $this->updateUser($user);
    }

    public function setUserPassword($user, $userPassword)
    {
        /** @var User $user */
        $user->setUserPassword($userPassword);
        $this->updateUser($user);
    }

    public function updateUser($user)
    {
        $userService = new UserService();
        $userService->updateUser($user);
    }

    public function createUser($user)
    {
        $userService = new UserService();
        $userService->createUser($user);
    }

    public function createGameAccount($gameAccount)
    {
        $gameAccountService = new GameAccountService();
        $gameAccountService->createGameAccount($gameAccount);
    }

    public function getGameAccountByAccountID($accountID)
    {
        $gameAccountService = new GameAccountService();
        $incompleteGameAccounts = $gameAccountService->getGameAccountByAccountID($accountID);
        $characterService = new CharacterService();
        $completeCharacters = $characterService->getCharactersByAccountID($accountID);
        $completeGameAccounts = $this->createGameAccountArray($incompleteGameAccounts, $completeCharacters);
        return $completeGameAccounts[0];
    }

    public function updateGameAccount($gameAccount)
    {
        $gameAccountService = new GameAccountService();
        $gameAccountService->updateGameAccount($gameAccount);
    }

    public function deleteGameAccount($gameAccount)
    {
        $gameAccountService = new GameAccountService();
        $gameAccountService->deleteGameAccount($gameAccount);
    }

    public function createCharacter($character)
    {
        $characterService = new CharacterService();
        $characterService->createCharacter($character);
    }

    public function updateCharacter($character)
    {
        $characterService = new CharacterService();
        $characterService->updateCharacter($character);
    }

    public function deleteCharacter($character)
    {
        $characterService = new CharacterService();
        $characterService->deleteCharacter($character);
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
            while ($gameAccountResults[$gameAccountPointer]["userID"] <= $row["userID"] && isset($gameAccountResults[$gameAccountPointer])) {
                /** @var GameAccount $gameAccount */
                $gameAccount = GameAccount::create($gameAccountResults[$gameAccountPointer]["userID"],
                    $gameAccountResults[$gameAccountPointer]["accountID"], $gameAccountResults[$gameAccountPointer]["cooldown"]);
                $characterList = array();
                while ($characterResults[$characterPointer]["accountID"] == $gameAccountResults[$gameAccountPointer]["accountID"]) {
                    $character = Character::create($characterResults[$characterPointer]["accountID"],
                        $characterResults[$characterPointer]["charID"], $characterResults[$characterPointer]["charName"],
                        $characterResults[$characterPointer]["cooldown"], $characterResults[$characterPointer]["charClass"],
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
            $event = Event::create($row["eventID"], $row["eventType"], $row["startDate"], $row["completeDate"],
                $row["eventState"], $row["recurringEvent"], $row["dayOfWeek"], $row["hourOfDay"], $row["eventName"]);
            $dropList = array();
            while ($dropResults[$dropPointer]["eventID"] == $row["eventID"]) {
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
            while ($slotResults[$slotPointer]["eventID"] == $row["eventID"]) {
                /** @var Slot $slot */
                $slot = Slot::create($slotResults[$slotPointer]["eventID"], $slotResults[$slotPointer]["slotID"],
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

    private function createGameAccountArray($gameAccountResults, $characterResults)
    {
        $result = array();
        $characterPointer = 0;
        foreach ($gameAccountResults as $row) {

            $gameAccount = GameAccount::create($row["userID"], $row["accountID"], $row["cooldown"]);
            $characterList = array();
            while ($characterResults[$characterPointer]["accountID"] <= $row["accountID"] && isset($characterResults[$characterPointer])) {
                $character = Character::create($characterResults[$characterPointer]["accountID"],
                    $characterResults[$characterPointer]["charID"], $characterResults[$characterPointer]["charName"],
                    $characterResults[$characterPointer]["cooldown"], $characterResults[$characterPointer]["charClass"],
                    $characterResults[$characterPointer]["userID"]);
                array_push($characterList, $character);
                $characterPointer++;
            }
            $gameAccount->setCharacterList($characterList);
            array_push($result, $gameAccount);
        }
        return $result;
    }
}