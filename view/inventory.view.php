<!DOCTYPE html>
<html>
<head>
    <title>LandingsPage</title>
    <?php include('partials/headers.partial.view.php') ?>
</head>
<body role="document">
<?php include('partials/navbar.partial.view.php') ?>

<?php if (isset($notification)): ?>
    <div class="alert alert-<?php print($notification['type']); ?> alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <p><strong><?php print($notification['title']); ?></strong> - <?php print($notification['message']); ?></p>
    </div>
<?php endif; ?>

<div class="container" role="main">
    <h3>Inventory Listing</h3>
    <table class="table table-hover table-striped">
        <thead>
        <tr>
            <th>
                ID
            </th>
            <th>
                Item Name (DB Name)
            </th>
            <th>
                Total Units
            </th>
            <th>
                Units Sold
            </th>
            <th>
                Average Price Sold
            </th>
            <?php if ($canEditInventory) : ?>
                <th>
                    Action
                </th>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($collatedDropsArray as $collatedDrop) :
        /** @var CollatedDrop $collatedDrop */?>
            <tr>
                <td><?php print $collatedDrop->getItemID(); ?></td>
                <td><?php print $collatedDrop->getItemName() . " (" . $collatedDrop->getAegisName() . ")" ; ?></td>
                <td><?php print $collatedDrop->getTotalUnits(); ?></td>
                <td><?php print $collatedDrop->getSoldUnits(); ?></td>
                <td><?php $collatedDrop->getSoldUnits() == 0 ? print 0 : print $collatedDrop->getTotalSoldValue() / $collatedDrop->getSoldUnits() ; ?></td>
                <?php if ($canEditInventory) : ?>
                    <td>

                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>



<?php include('partials/footer.partial.view.php') ?>
</body>
</html>