<!DOCTYPE html>
<html>
<head>
    <title>LandingsPage</title>
    <?php include('partials/headers.partial.view.php') ?>
</head>
<body role="document">
<?php include('partials/navbar.partial.view.php') ?>
<div class="container" role="main">
    <div class="row">
        <?php
        foreach ($eventContainer as $event) :
            /** @var Event $event */
            $dropList = $event->getDropList();
            ?>

            <div class="col-sm-4">
                <div class="panel panel-warning">
                    <div class="panel-heading"
                         title="<?php print($event->getEventName()); ?> - <?php print($event->getStartDate()); ?>">
                        <h3 class="panel-title">
                            <div class="pull-right">
                                <?php
                                $totalValue = 0;
                                foreach ($dropList as $drop) {
                                    /** @var Drop $drop */
                                    if ($drop->isSold()) {
                                        $totalValue += $drop->getSoldPrice();
                                    }
                                }
                                print($totalValue);
                                ?>
                            </div>
                            <?php
                            if (strlen($event->getEventName()) > 20):
                                print(substr($event->getEventName(), 0, 20) . '...');
                            else:
                                print($event->getEventName());
                            endif;
                            ?>
                        </h3>
                    </div>
                    <div class="panel-body" style="font-size: 10px;">
                        <div class="col-sm-3">
                            <?php
                            foreach (($event->getSlotList()) as $slot) :
                                /** @var Slot $slot */
                                if ($slot->getTakenUserID()) {
                                    print($slot->getUserLogin());
                                    print("<br >");
                                }
                            endforeach; ?>
                        </div>

                        <div class="col-sm-9">
                            <?php foreach ($event->getDropList() as $drop) : ?>
                                <div class="col-sm-6">
                                    <?php if (strlen($drop->getItemName()) > 10): ?>
                                        <span title="<?php print($drop->getItemName()); ?>">
                                                    <?php print(substr($drop->getItemName(), 0, 10) . '...'); ?>
                                                </span>
                                    <?php
                                    else :
                                        print($drop->getItemName());
                                    endif; ?>
                                </div>
                                <div class="col-sm-6" style="text-align: right; font-weight: bold;">
                                    <?php print(number_format($drop->getSoldPrice())); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>


    <!--        <br>
        <?php
    //foreach ($eventlist as $event) {
    ?>
            <table class="featured-node event-item">
                <tr>

                    <td class="event-itemlist">
                        <table style="width: 100%">
                            <?php
    //foreach ($event->getDrops() as $drop) {
    ?>
                                <tr>
                                    <td>
                                        <?php //if (strlen($drop->getName()) > 15): ?>
                                            <span title="<?php //print($drop->getName()); ?>">
                                                    <?php //print(substr($drop->getName(), 0, 15) . '...'); ?>
                                                </span>
                                        <?php
    //else: print($drop->getName());
    // endif;
    ?>
                                    </td>
                                    <td style="text-align: right; font-weight: bold;padding-left: 5px;">
                                        <?php //print(number_format($drop->getDropValue())); ?>
                                    </td>
                                </tr>
                            <?php
    //   }
    ?>
                        </table>

                    </td>
                </tr>
            </table>
        <?php
    //   }
    ?>
-->
    <div style="clear:both;"></div>
</div>


<?php include('partials/footer.partial.view.php') ?>
</body>
</html>