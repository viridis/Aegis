<!DOCTYPE html>
<html>
<head>
    <title>LandingsPage</title>
    <?php include('headers.partial.view.php') ?>
</head>

<body>
<?php include('navbar.partial.view.php') ?>

<div class="content">
    <div class="popupWrapper" id="popupWrapper"></div>
    <div class="popup" id="popAddItem">
        <form action="items.php" method="post">
            <input name="addItem" id="addItem" type="text" class="inputText" placeholder="Item Name">
            <input type="submit" class="mySubmitButton" value="Add Item">
        </form>
    </div>
    <div class="popup" id="popSellItem">
        <form action="items.php" method="post" style="text-align: left;">
            <input name="itemAmount" type="text" class="inputText" id="itemAmount" placeholder="#" value="1"
                   style="width: 20px;" autocomplete="off">
            <select data-placeholder="Pick an item..." class="chosen-select" id="itemName" name="itemName">
                <option value=""></option>
                <?php foreach ($itemlist as $item) { ?>
                    <option value="<?php print($item->getId()); ?>"><?php print($item->getName()); ?></option>
                <?php } ?>
            </select>
            <input name="itemValue" type="text" class="inputText number" id="itemValue" placeholder="Item Value"
                   autocomplete="off">
            <input type="submit" class="mySubmitButton" value="Sell Item">
        </form>
    </div>
    <div class="popup" id="popEditItem">
        <form id="submitEditItem" action="items.php" method="post">
            <input name="editItemID" id="editItemID" type="hidden" class="inputText" placeholder="Item ID">
            <input name="editItemName" id="editItemName" type="text" class="inputText" placeholder="Item Name">
            <input type="submit" class="mySubmitButton" value="Edit Item">
        </form>
    </div>
    <?php if (isset($notification)): ?>
        <br/>
        <div class="<?php print($notification['type']); ?>">
            <h1><?php print($notification['message']); ?></h1>
        </div>
        <br/>
    <?php endif; ?>
    <div class="buttonContainer">
        <input id="addItemButton" type="submit" class="myButton" value="Add Item">
        <input id="sellItemButton" type="submit" class="myButton" value="Sell Item">
    </div>
    <div class="featured-node" style="margin-top: 20px;">
        <table>
            <tr>
                <td style="width: 20px; padding: 2px 5px;"></td>
                <td style="padding: 2px 5px;">Registered Items</td>
                <td></td>
            </tr>
            <?php
            $i = 0;
            foreach ($itemlist as $item) {
                $i++;
                ?>
                <tr>
                    <td style="width: 20px; padding: 2px 5px;"><?php print($i); ?></td>
                    <td style="padding: 2px 5px;"><?php print($item->getName()); ?></td>
                    <td>
                        <img src="../assets/edit_icon.png" class="editIcon"
                             onclick="editItem(<?php print($item->getId()); ?>);">
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
</div>


<?php include('footer.partial.view.php') ?>
<link rel="stylesheet" type="text/css" href="../assets/chosen.css">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script type="text/javascript" src="../assets/chosen.jquery.js"></script>
<script type="text/javascript">
    window.onload = function () {
        initializePage();
        document.getElementById("addItemButton").onclick = function (event) {
            popup('popAddItem', 'block');
            setFocus('addItem');
        }
        document.getElementById("sellItemButton").onclick = function (event) {
            popup('popSellItem', 'block');
            $("#itemName_chosen").children('.chosen-drop').children('.chosen-search').children('input[type="text"]').focus();
        }
        document.getElementById("popupWrapper").onclick = function (event) {
            popup('popAddItem', 'none');
            popup('popSellItem', 'none');
            popup('popEditItem', 'none');
        }
        document.getElementById("itemValue").onkeyup = function (event) {

            event.target.value = commaSeparateNumber(event.target.value);
        }
    }
    window.onresize = initializePage;

    //close popup boxes using ESC key.
    document.onkeydown = function (event) {
        event = event || window.event;
        if (event.keyCode == 27) {
            popup('popSellItem', 'none');
            popup('popSellItem', 'none');
        }
    }

    var config = {
        '.chosen-select': {width: "200px"},
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
                    popup('popEditItem', 'block');
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