<!DOCTYPE html>
<html>
<head>
    <title>LandingsPage</title>
    <?php include('partials/headers.partial.view.php') ?>
</head>
<body role="document">
<?php include('partials/navbar.partial.view.php') ?>
<div class="container" role="main">
    <?php if (isset($notification)): ?>
        <div class="alert alert-<?php print($notification['type']); ?> alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <p><strong><?php print($notification['title']); ?></strong> - <?php print($notification['message']); ?></p>
        </div>
    <?php endif; ?>
    <h3>Manage Drops from Events</h3>

    <?php if ($canAdmin) : ?>
    <form action="participate.php" method="get">
        <div>
            <input type="hidden" name="admin" value="true">
            <button type="submit">Admin View</button>
        </div>
    </form>
    <?php endif; ?>

    <div class="panel-group" id="eventPanels">
        <?php foreach ($eventContainer as $event) :
            /** @var Event $event */
            $eventDate = strtotime($event->getStartDate());
            $displayEventDate = date('d M gA', $eventDate+($currentUser->getGMT()*3600));
            $hoursToEvent = round(($eventDate-time())/3600,1);
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <a data-toggle="collapse" data-parent="#eventPanels"
                           href="#event<?php print $event->getEventID(); ?>" class="collapsed">
                            <?php print $event->getEventID() . ". " . $event->getEventName() . " , " . $displayEventDate . " , " . $hoursToEvent . " hour(s) later" ?>
                        </a>
                        <a data-toggle="collapse" data-parent="#eventPanels"
                           href="#event<?php print $event->getEventID(); ?>" class="collapsed" style="float:right;">
                            <?php
                            $totalSlot = 0;
                            $filledSlot = 0;
                            foreach ($event->getSlotList() as $slot) :
                                /** @var Slot $slot */
                                $totalSlot++;
                                if ($slot->isTaken()) :
                                    $filledSlot++;
                                endif;
                            endforeach;
                            print "Occupancy: " . $filledSlot . "/" . $totalSlot;
                            ?>
                        </a>
                    </h3>
                </div>
                <div id="event<?php print $event->getEventID(); ?>" class="panel-collapse collapse">
                    <div class="panel-body">
                        <table class="table">
                            <?php
                            $slotNum = 0;
                            foreach ($event->getSlotList() as $slot) :
                                $slotNum++;?>

                                <tr>

                                <td><?php print $slotNum . ". "; ?></td>
                                <td> <?php print $slot->getSlotClassName(); ?></td>
                                <?php if (($slot->isTaken()) && ($slot->getTakenUserID() != $_SESSION["userID"]) && !$isAdmin): ?>
                                <td><?php print $slot->getCharName() . " (" . $slot->getTakenCharClassName() . ")"    ; ?></td>
                            <?php else : ?>

                                <td>
                                    <form class="form-horizontal"
                                          action="participate.php?<?php $isAdmin ? print "admin&" : print "" ?>updateSlot=<?php print $slot->getSlotID(); ?>"
                                          method="post">
                                        <?php if ($slot->isTaken()) : ?>
                                        <Select name="change_slot_<?php print $slot->getSlotID(); ?>">
                                            <?php else : ?>
                                            <Select name="join_slot_<?php print $slot->getSlotID(); ?>">
                                                <option selected value="">Choose character!</option>
                                                <?php endif; ?>
                                                <?php foreach ($validCharactersForSlotTypes[$event->getEventTypeID()][$slot->getSlotClassID()] as $validChar) :
                                                    /** @var Character $validChar */

                                                    $cooldownDate = 0;
                                                    if (isset($validChar->getCooldownContainer()[$event->getEventTypeID()])) :
                                                        $cooldownDate = strtotime($validChar->getCooldownContainer()[$event->getEventTypeID()]);
                                                    endif;

                                                    if ($eventDate > $cooldownDate) : ?>
                                                        <option <?php if ($slot->isTaken() && ($validChar->getCharID() == $slot->getTakenCharID())) print "selected" ?>
                                                            value="<?php print $validChar->getCharID(); ?>"><?php print $validChar->getCharName() . " (" . $validChar->getCharClassName() . ")" ?></option>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </Select>

                                            <input type="submit" class="btn btn-xs btn-primary"
                                                   value="<?php ($slot->isTaken()) ? print "Change character!" : print "Join!"; ?>">
                                    </form>
                                </td>
                                <?php if ($slot->isTaken()) : ?>
                                    <td>
                                        <form class="form-horizontal"
                                              action="participate.php?<?php $isAdmin ? print "admin&" : print "" ?>updateSlot=<?php print $slot->getSlotID(); ?>"
                                              method="post">
                                            <input type="hidden" name="vacate_slot_<?php print $slot->getSlotID(); ?>"
                                                   value="<?php print $slot->getSlotID(); ?>">
                                            <input type="submit" class="btn btn-xs btn-warning"
                                                   value="Vacate Slot">
                                        </form>
                                    </td>
                                <?php endif; ?>
                                </tr>
                            <?php endif; ?>

                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>


</div>
<?php include('partials/footer.partial.view.php') ?>
</body>
</html>