<!DOCTYPE html>
<html>
<head>
    <title>LandingsPage</title>
    <?php include('partials/headers.partial.view.php') ?>
</head>
<body role="document">
<?php include('partials/navbar.partial.view.php') ?>

<div class="container" role="main">
    <h3>Participate in Events</h3>

    <div class="panel-group" id="eventPanels">
        <?php foreach ($eventContainer as $event) :
            /** @var Event $event */
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <a data-toggle="collapse" data-parent="#eventPanels"
                           href="#event<?php print $event->getEventID(); ?>" class="collapsed">
                            <?php print $event->getEventID() . ". " . $event->getEventName() . " - " . $event->getStartDate(); ?>

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
                        <form class="form-horizontal" action="participate.php?joinEvent=<?php print $event->getEventID() ?>" method="post">
                        <?php
                        $slotNum = 0;
                        foreach ($event->getSlotList() as $slot) :
                            $slotNum++;?>
                            <p>
                                <?php
                                print $slotNum . ". ";
                                print $slot->getSlotClassID();
                                if ($slot->isTaken()) : ?>
                                    <input type="text" value="<?php print $slot->getCharName(); ?>" readonly>
                                <?php else : ?>
                                    
                                <? endif ; ?>
                            </p>
                        <?php endforeach; ?>
                        </form>
                    </div>
                </div>
            </div>

        <?php endforeach ?>
    </div>


</div>
<script>

</script>
<?php include('partials/footer.partial.view.php') ?>
</body>
</html>