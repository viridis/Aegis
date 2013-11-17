<!DOCTYPE html>
<html>
<head>
    <title>LandingsPage</title>
    <?php include('headers.partial.view.php') ?>
    <script>
        window.onload = function () {
            initializePage();
        }

        window.onresize = initializePage;
    </script>
</head>

<body>
<?php include('navbar.partial.view.php') ?>

<div class="content">
    <br>
    <?php
    foreach ($eventlist as $event) {
        ?>
        <table class="featured-node event-item">
            <thead>
            <td colspan="2" class="event-title" title="<?php print($event->getName()); ?>">
                <?php
                if (strlen($event->getName()) > 20):
                    print(substr($event->getName(), 0, 20) . '...');
                else:
                    print($event->getName());
                endif;
                ?>
                <span class="event-money">
						    <?php print(number_format($event->getTotalValue())); ?>
                        </span>
            </td>
            </thead>
            <tr>
                <td colspan="2" class="event-title">
                    <?php print($event->getTime()); ?>
                </td>
            </tr>
            <tr>
                <td class="event-userlist">
                    <?php
                    foreach ($event->getParticipants() as $participant) {
                        print($participant->getName());
                        print("<br >");
                    }
                    ?>
                </td>

                <td class="event-itemlist">
                    <table style="width: 100%">
                        <?php
                        foreach ($event->getDrops() as $drop) {
                            ?>
                            <tr>
                                <td>
                                    <?php if (strlen($drop->getName()) > 15): ?>
                                        <span title="<?php print($drop->getName()); ?>">
                                                <?php print(substr($drop->getName(), 0, 15) . '...'); ?>
                                            </span>
                                    <?php
                                    else: print($drop->getName());
                                    endif; ?>
                                </td>
                                <td style="text-align: right; font-weight: bold;padding-left: 5px;">
                                    <?php print(number_format($drop->getDropValue())); ?>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>

                </td>
            </tr>
        </table>
    <?php
    }
    ?>

    <div style="clear:both;"></div>
</div>


<?php include('footer.partial.view.php') ?>
</body>
</html>