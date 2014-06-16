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
            /** @var CollatedDrop $collatedDrop */
            ?>
            <tr id="row<?php print $collatedDrop->getItemID(); ?>">
                <td><?php print $collatedDrop->getItemID(); ?></td>
                <td><?php print $collatedDrop->getItemName() . " (" . $collatedDrop->getAegisName() . ")"; ?></td>
                <td><?php print $collatedDrop->getTotalUnits(); ?></td>
                <td><span id="row<?php print $collatedDrop->getItemID();?>_colSold"><?php print $collatedDrop->getSoldUnits(); ?></span></td>
                <td><span id="row<?php print $collatedDrop->getItemID();?>_colSoldPrice"><?php $collatedDrop->getSoldUnits() == 0 ? print 0 : print $collatedDrop->getTotalSoldValue() / $collatedDrop->getSoldUnits(); ?></span></td>
                <?php if ($canEditInventory) : ?>
                    <td>
                        <a class="btn btn-xs btn-warning"
                           id="sell_item_<?php print $collatedDrop->getItemID() . "_" . $collatedDrop->getItemName(); ?>">Sell
                            Item</a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>



<?php include('partials/footer.partial.view.php') ?>
<script type="text/javascript">
    var index = 0;
    $(".btn-warning").click(function (event) {
        event.preventDefault();
        var id = event.target.id;
        var result = id.split('_');
        index++;
        $('body').prepend('<div id="dataConfirmModal' + index + '" class="modal fade">' +
            '<div class="modal-dialog modal-md">' +
            '<div class ="modal-content">' +
            '<div class="modal-header">' +
            '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">Sell Item (' + result[3] + ')</h4>' +
            '</div>' +
            '<form class="form-horizontal">' +
            '<div class="modal-body"> ' +
            '<div class="form-group">' +
            '<input name="' + index + '_itemID" id="' + index + '_itemID" type="hidden" value="' + result[2] + '">' +
            '<label for="sellAmount" class="col-sm-3 control-label">Sell Amount</label>' +
            '<div class="col-sm-9"> <input type="text" class="form-control" id="' + index + '_sellAmount" name="' + index + '_sellAmount" placeholder="Number sold"></div>' +
            '<label for="sellPrice" class="col-sm-3 control-label">Sell Price</label>' +
            '<div class="col-sm-9"> <input type="text" class="form-control" id="' + index + '_sellPrice" name="' + index + '_sellPrice" placeholder="Zeny per piece"></div>' +
            '</div></div>' +
            '<div class="modal-footer">' +
            '<div class="col-sm-3">' +
            '<button class="btn" class="close" data-dismiss="modal" aria-hidden="true">Cancel</button>' +
            '</div>' + '<div class="col-sm-9">' +
            '<input type="button" class="btn" class="close" data-dismiss="modal" value="Sell Item" id="' + index + '_SellButton" onclick="sendSellItemRequest(this); return false;">' +
            '</div></div></form></div></div>');
        $('#dataConfirmModal' + index).modal({show: true});
    });

    function sendSellItemRequest(object) {
        var index = $(object).attr('id').split('_')[0];
        var itemID = $('#' + index + '_itemID').attr('value');
        var sellAmount = $('#' + index + '_sellAmount').val();
        var sellPrice = $('#' + index + '_sellPrice').val();
        makeAddAjaxRequest(itemID, sellAmount, sellPrice);
    }

    function makeAddAjaxRequest(itemID, sellAmount, sellPrice) {

        $.ajax({
            type: "POST",
            data: { itemID: itemID, sellAmount: sellAmount, sellPrice: sellPrice},
            url: "inventory.php",
            success: function (result) {
                processJSONDisplayTable(result);
            }
        });
    }

    function processJSONDisplayTable(result) {
        $('#row501_colSold').html("1");
        $('#row501_colSoldPrice').html("100000");
    }
</script>
</body>
</html>