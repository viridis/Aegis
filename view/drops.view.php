<!DOCTYPE html>
<html>
<head>
    <title>LandingsPage</title>
    <?php include('partials/headers.partial.view.php'); ?>

</head>
<body role="document">
<?php include('partials/navbar.partial.view.php'); ?>

<div class="container" role="main">
    <?php if (isset($notification)): ?>
        <div class="alert alert-<?php print($notification['type']); ?> alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <p><strong><?php print($notification['title']); ?></strong> - <?php print($notification['message']); ?></p>
        </div>
    <?php endif; ?>
    <h3>Participate in Events</h3>

    <div class="panel-group" id="eventPanels">
        <?php foreach ($eventContainer as $event) :
            /** @var Event $event */
            $eventDate = strtotime($event->getCompleteDate());
            $displayEventDate = date('d M gA', $eventDate + ($currentUser->getGMT() * 3600));
            $hoursToEvent = round((time() - $eventDate) / 3600, 1);
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <a data-toggle="collapse" data-parent="#eventPanels"
                           href="#event<?php print $event->getEventID(); ?>" class="collapsed">
                            <?php print $event->getEventID() . ". " . $event->getEventName() . " , " . $displayEventDate . " , " . $hoursToEvent . " hour(s) ago" ?>
                        </a>
                    </h3>
                </div>
                <div id="event<?php print $event->getEventID(); ?>" class="panel-collapse collapse">
                    <div class="panel-body">
                        <form action="drops.php" method="post" class="form-horizontal">
                            <label for="addDrop_<?php print $event->getEventID(); ?>">Add Drop</label>
                            <input size="30" id="addDrop_<?php print $event->getEventID(); ?>" class="addDrop"
                                   name="addDrop">
                            <input type="button" class="btn btn-xs btn-primary" value="Add Drop"
                                   id="addDropButton_<?php print $event->getEventID(); ?>"
                                   onclick="sendAddDropRequest(this); return false;">
                        </form>
                        <table id="dropTable_<?php print $event->getEventID(); ?>"
                               class="table table-condensed table-hover table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>
                                    Drop Name
                                </th>
                                <th>
                                    Amount
                                </th>
                                <th>
                                    Action
                                </th>
                            </tr>
                            <?php foreach ($eventDropCollation[$event->getEventID()] as $dropArray) :

                                /** @var Drop $drop */
                                ?>
                                <tr>
                                    <td><?php print $dropArray[0]->getItemName() . " (" . $dropArray[0]->getAegisName() . ")"; ?></td>
                                    <td><?php print sizeof($dropArray); ?></td>
                                    <td><input type="button" class="btn btn-xs btn-warning" value="Remove 1"
                                               id="removeDropButton_<?php print $event->getEventID(); ?>_<?php print $dropArray[0]->getItemID(); ?>"
                                               onclick="sendRemoveDropRequest(this); return false;"></td>
                                </tr>
                            <?php endforeach; ?>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include('partials/footer.partial.view.php') ?>
<script>
    $(function () {
        var availableTags = [
            <?php foreach ($itemContainer as $item)  {
            /** @var Item $item */
             print "\"" . $item->getItemID() . ": " . $item->getName()  . " (" . $item->getAegisName() . ")\",";} ?>
        ];
        $(".addDrop").autocomplete({
            source: availableTags,
            minLength: 3
        });
    });

    function sendAddDropRequest(object) {
        var assocInput = 'addDrop_' + $(object).attr('id').split('_')[1];
        var eventID = $(object).attr('id').split('_')[1];
        var selectedItem = $('#' + assocInput).val();
        makeAddAjaxRequest(selectedItem, eventID);
    }

    function makeAddAjaxRequest(itemID, eventID) {

        $.ajax({
            type: "POST",
            data: { addDrop: itemID, eventID: eventID},
            url: "drops.php",
            success: function (result) {
                processJSONDisplayTable(result);
            }
        });
    }

    function processJSONDisplayTable(result) {
        var resultArray = result.split('|');
        var event = jQuery.parseJSON(resultArray[0]);
        $("#dropTable_" + event.eventID).find("tr:gt(0)").remove();
        for (var i = 1; i < resultArray.length; i++) {
            var drop = jQuery.parseJSON(resultArray[i]);
            $('#dropTable_' + event.eventID + ' tr:last').after('<tr><td>' + drop.itemName + ' (' + drop.aegisName + ')</td><td>' + drop.count + '</td><td>' + '<input type="button" class="btn btn-xs btn-warning" value="Remove 1" id="removeDropButton_' + event.eventID + '_' + drop.itemID + '" onclick="sendRemoveDropRequest(this); return false;">' + '</tr>');
        }
    }

    function sendRemoveDropRequest(object) {
        var eventID = $(object).attr('id').split('_')[1];
        var itemID = $(object).attr('id').split('_')[2];
        makeRemoveAjaxRequest(itemID, eventID);
    }

    function makeRemoveAjaxRequest(itemID, eventID) {

        $.ajax({
            type: "POST",
            data: { removeDrop: itemID, eventID: eventID},
            url: "drops.php",
            success: function (result) {
                processJSONDisplayTable(result);
            }
        });
    }
</script>
</body>

</html>