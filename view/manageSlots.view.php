<!DOCTYPE html>
<html>
<head>
    <title>LandingsPage</title>
    <?php include('partials/headers.partial.view.php'); ?>
</head>
<body role="document">
<?php include('partials/navbar.partial.view.php'); ?>

<div class="container" role="main">
    <h3>Manage Slot Classes</h3>
    Note: click to edit, enter to save
    <table class="table table-bordered" id="slotClassRuleTable">
        <thead>
        <tr>
            <th>Slot Class Name</th>
            <?php foreach ($charClassArray as $charClass) :
                /** @var CharClass $charClass */
                ?>
                <th><?php print $charClass->getCharClassName() ?></th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($slotClassArray as $slotClass) :
            /** @var SlotClass $slotClass */
            ?>
            <tr>
                <td class="edit"
                    id="slotClassName_<?php print $slotClass->getSlotClassID(); ?>"><?php print $slotClass->getSlotClassName(); ?></td>
                <?php foreach ($charClassArray as $charClass) : ?>
                    <td class="edit"
                        id="<?php print "slotClassRule_" . $slotClass->getSlotClassID() . "_" . $charClass->getCharClassID(); ?>"><?php isset($slotClass->getAllowedCharClassArray()[$charClass->getCharClassID()]) ? print "Y" : print  "N"; ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="container col-sm-6">
    <form class="form-horizontal">
        <div class="form-group">
            <label for="newSlotClassName" class="control-label col-sm-4">New Slot Class</label>
            <div class="col-sm-4">
            <input type="text" class="form-control" id="newSlotClassName" name="newSlotClassName"
                   placeholder="Slot Class Name">
            </div>
            <div class="col-sm-2">
                <input type="button" class="btn btn-primary" value="Create"
                       id="createSlotClassButton" onclick="createSlotClass(); return false;">
            </div>
        </div>
    </form>
</div>
</div>
<?php include('partials/footer.partial.view.php'); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('.edit').editable('manageSlots.php', {
            id: 'id',
            name: 'value'
        });
    });

    function createSlotClass() {
        var newSlotClassName = $('#newSlotClassName').val();
        makeAddAjaxRequest(newSlotClassName);
    }

    function makeAddAjaxRequest(newSlotClassName) {

        $.ajax({
            type: "POST",
            data: { newSlotClassName: newSlotClassName},
            url: "manageSlots.php",
            success: function (result) {
                processJSONUpdateTable(result);
            }
        });
    }

    function processJSONUpdateTable(result) {
        $('#slotClassRuleTable tr:last').after(result);
    }
</script>
</body>

</html>