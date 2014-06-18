
<!DOCTYPE html>
<html>
<head>
    <title>LandingsPage</title>
    <?php include('partials/headers.partial.view.php'); ?>
</head>
<body role="document">
<?php include('partials/navbar.partial.view.php'); ?>

<div class="container" role="main">
    <h3>Manage Event Templates</h3>
    Note: click to edit, enter to save
    <table class="table table-bordered" id="eventTemplateTable">
        <thead>
        <tr>
            <th>Event Type Name</th>
            <th>Character Cooldown (s)</th>
            <th>Account Cooldown (s)</th>
            <th>Slots on creation</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($eventTypeArray as $eventType) :
            /** @var EventType $eventType */ ?>
        <tr>
            <td class="edit" id="<?php print "eventTypeName_" . $eventType->getEventTypeID();?>"><?php print $eventType->getEventName();?></td>
            <td class="edit" id="<?php print "characterCooldown_" . $eventType->getEventTypeID();?>"><?php print $eventType->getCharacterCooldown(); ?></td>
            <td class="edit" id="<?php print "accountCooldown_" . $eventType->getEventTypeID();?>"><?php print $eventType->getAccountCooldown(); ?></td>
            <td class="edit" id="<?php print "numSlots_" . $eventType->getEventTypeID();?>"><?php print $eventType->getNumSlots(); ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="container col-sm-6">
        <form class="form-horizontal">
            <div class="form-group">
                <label for="newEventTypeName" class="control-label col-sm-4">New Event Template</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="newEventTypeName" name="newEventTypeName"
                           placeholder="Event Template Name">
                </div>
                <div class="col-sm-2">
                    <input type="button" class="btn btn-primary" value="Create"
                           id="createEventTypeButton" onclick="createEventType(); return false;">
                </div>
            </div>
        </form>
    </div>
</div>
<?php include('partials/footer.partial.view.php'); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('.edit').editable('manageEventTemplate.php', {
            id: 'id',
            name: 'value'
        });
    });

    function createEventType() {
        var newEventTypeName = $('#newEventTypeName').val();
        makeAddAjaxRequest(newEventTypeName);
    }

    function makeAddAjaxRequest(newEventTypeName) {

        $.ajax({
            type: "POST",
            data: { newEventTypeName: newEventTypeName},
            url: "manageEventTemplate.php",
            success: function (result) {
                processJSONUpdateTable(result);
            }
        });
    }

    function processJSONUpdateTable(result) {
        $('#eventTemplateTable tr:last').after(result);
    }
</script>
</body>

</html>