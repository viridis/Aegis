<!DOCTYPE html>
<html>
<head>
    <title>LandingsPage</title>
    <?php include('partials/headers.partial.view.php') ?>
</head>
<body role="document">
    <?php include('partials/navbar.partial.view.php') ?>
    <div style="height: 20px;"></div>
    <div class="container" role="main">

        <div class="modal fade" id="addItem">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Add an item</h4>
                    </div>
                    <form action="items.php" method="post">
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" class="form-control" id="addItem" name="addItem" placeholder="Item Name">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-primary" value="Add Item">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="sellItem">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Sell an item</h4>
                    </div>
                    <form action="items.php" method="post" class="form-horizontal">
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <input name="itemAmount" type="text" class="form-control" id="itemAmount" placeholder="#" value="1" autocomplete="off">
                                </div>
                                <div class="col-md-5">
                                    <select data-placeholder="Pick an item..." class="chosen-select" id="itemName" name="itemName">
                                        <option value=""></option>
                                        <?php foreach ($itemlist as $item) { ?>
                                            <option value="<?php print($item->getId()); ?>"><?php print($item->getName()); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <input name="itemValue" type="text" class="form-control" id="itemValue" placeholder="Item Value" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-primary" value="Sell Item">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editItem">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Edit an item</h4>
                    </div>
                    <form id="submitEditItem" action="items.php" method="post">
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="hidden" class="form-control" id="editItemID" name="editItemID" placeholder="Item ID">
                                <input type="text" class="form-control" id="editItemName" name="editItemName" placeholder="Item Name">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-primary" value="Edit Item">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php if (isset($notification)): ?>
            <div class="alert alert-<?php print($notification['type']); ?> alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <p><strong><?php print($notification['title']); ?></strong> - <?php print($notification['message']); ?></p>
            </div>
        <?php endif; ?>
        <div style="height: 20px;"></div>

        <div class="pull-right btn-group-vertical">
            <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#addItem">
                Add Item
            </button>
            <button id="sellItemButton" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#sellItem">
                Sell Item
            </button>
        </div>

        <div class="container">
            <div class="row center">
                <div class="col-sm-4 col-md-offset-4">
                    <table class="table table-condensed table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Registered Items</th>
                            <th></th>
                        </tr>
                        </thead>
                        <?php
                        $i = 0;
                        foreach ($itemlist as $item) {
                            $i++;
                            ?>
                            <tr>
                                <td><?php print($i); ?></td>
                                <td><?php print($item->getName()); ?></td>
                                <td>
                                    <span class="glyphicon glyphicon-pencil hand" onclick="editItem(<?php print($item->getId()); ?>);" data-toggle="modal" data-target="#editItem"></span>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <?php include('partials/footer.partial.view.php') ?>
    <link rel="stylesheet" type="text/css" href="../assets/css/chosen.css">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <script type="text/javascript" src="../assets/js/chosen.jquery.js"></script>
    <script type="text/javascript">

        var config = {
            '.chosen-select': {width: "100%"},
            '.chosen-select-deselect': {allow_single_deselect: true},
            '.chosen-select-no-single': {disable_search_threshold: 10},
            '.chosen-select-no-results': {no_results_text: 'Oops, nothing found!'},
            '.chosen-select-width': {width: "95%"}
        }
        for (var selector in config) {
            $(selector).chosen(config[selector]);
        }

        function editItem(id) {
            makeRequest("items.php?editItem=" + id);
        }

        function serverResponse(httpRequest) {
            if (httpRequest.readyState === 4) {
                if (httpRequest.status === 200) {
                    result = JSON.parse(httpRequest.responseText);
                    if (result['action'] == "requestItem") {
                        document.getElementById("editItemID").value = result['item']['id'];
                        document.getElementById("editItemName").value = result['item']['name'];
                    }
                }
                else {
                    alert('There was a problem with the request.');
                }
            }
        }

        function makeRequest(url) {
            var httpRequest;
            if (window.XMLHttpRequest) { // Mozilla, Safari, ...
                httpRequest = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) { // IE
                try {
                    httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
                }
                catch (e) {
                    try {
                        httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    catch (e) {
                    }
                }
            }

            if (!httpRequest) {
                alert('Giving up :( Cannot create an XMLHTTP instance');
                return false;
            }
            httpRequest.onreadystatechange = function () {
                serverResponse(httpRequest);
            };
            httpRequest.open('POST', url, true);
            httpRequest.send();
        }
    </script>
</body>
</html>