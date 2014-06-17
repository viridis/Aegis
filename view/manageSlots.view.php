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
    Note: click to edit
    <table class="table table-bordered">
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
                <td class="edit" id="slotClassName_<?php print $slotClass->getSlotClassID();?>"><?php print $slotClass->getSlotClassName();?></td>
                <?php foreach ($charClassArray as $charClass) : ?>
                    <td class="edit" id="<?php print "slotClassRule_" . $slotClass->getSlotClassID() . "_" . $charClass->getCharClassID(); ?>"><?php isset($slotClass->getAllowedCharClassArray()[$charClass->getCharClassID()]) ? print "Y" : print  "N" ;?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>
<?php include('partials/footer.partial.view.php'); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('.edit').editable('manageSlots.php', {
            id   : 'id',
            name : 'value'
        });
    });
</script>
</body>

</html>