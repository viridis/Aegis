<!DOCTYPE html>
<html>
<head>
    <title>LandingsPage</title>
    <?php include('partials/headers.partial.view.php'); ?>
</head>
<body role="document">
<?php include('partials/navbar.partial.view.php'); ?>

<div class="container" role="main">

    <div class="modal fade" id="addRun">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Add an item</h4>
                </div>
                <form action="runs.php?addRun=1" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" id="runName" name="runName" placeholder="Run Name">
                            <input type="date" class="form-control" id="runDate" name="runDate" placeholder="mm/dd/yyyy">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="Add Run">
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

    <?php if ($editing == 1): ?>
        <div class="jumbotron">
            <div class="pull-left trashIcon" id="bin" ondragover="return DragOver(event)" ondragleave="return DragLeave(event)" ondrop="return doDrop(event)">
                <span id="bin" class="glyphicon glyphicon-trash"></span>
            </div>

            <h3>
                <?php
                print($run->getEventName());
                print(" -- ");
                print($run->getStartDate());
                ?>
            </h3>
            <br>
            <div class="container">
                <div class="row">

                    <div class="col-sm-2">
                        <h4>Participants</h4>
                        <ul class="userList" id="users" class="dropable_lists" ondragover="return DragOver(event)"
                            ondragleave="return DragLeave(event)" ondrop="return doDrop(event)">
                            <?php
                            foreach ($slotlist as $slot) : ?>
                                <li id="db_users_<?php print($slot->getTakenUserID()); ?>" draggable='true'
                                    ondragstart="return drag(event)">
                                    <?php
                                    if ($slot->getTakenUserID() > 0)
                                        print(userservice::getUserByID($slot->getTakenUserID())->getUserLogin());
                                    else
                                        print("vacant"); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="col-sm-10">
                        <?php for ($i = 0; $i < count($userList); $i++) : ?>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php if (isset($userList[0][$i])): ?>
                                        <span id='users_<?php print($userList[0][$i]->getUserID()); ?>' draggable='true'
                                              ondragstart='return drag(event)' class="floatListItem">
                                    <?php print($userList[0][$i]->getUserLogin()); ?>
                                </span>
                                    <?php endif; ?>
                                </div>
                                <div class="col-sm-4">
                                    <?php if (isset($userList[1][$i])): ?>
                                        <span id='users_<?php print($userList[1][$i]->getUserID()); ?>' draggable='true'
                                              ondragstart='return drag(event)' class="floatListItem">
                                    <?php print($userList[1][$i]->getUserLogin()); ?>
                                </span>
                                    <?php endif; ?>
                                </div>
                                <div class="col-sm-4">
                                    <?php if (isset($userList[2][$i])): ?>
                                        <span id='users_<?php print($userList[2][$i]->getUserID()); ?>' draggable='true'
                                              ondragstart='return drag(event)' class="floatListItem">
                                    <?php print($userList[2][$i]->getUserLogin()); ?>
                                </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-sm-2">
                        <h4>Drops</h4>
                        <ul class="itemList" id="items" class="dropable_lists" ondragover="return DragOver(event)"
                            ondragleave="return DragLeave(event)" ondrop="return doDrop(event)">
                            <?php
                            foreach (dropservice::getDropByEventID($run->getEventID()) as $drop): ?>
                                <li id="db_items_<?php print($drop->getDropID()); ?>" draggable='true'
                                    ondragstart='return drag(event)'>
                                    <?php
                                    print(itemservice::getItemById($drop->getItemID())->getName()); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="col-sm-10">
                        <?php for ($i = 0; $i < count($itemList[0]); $i++) : ?>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php if (isset($itemList[0][$i])): ?>
                                        <span id='items_<?php print($itemList[0][$i]->getItemID()); ?>' draggable='true'
                                            ondragstart='return drag(event)' class="floatListItem">
                                            <?php print($itemList[0][$i]->getName()); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="col-sm-4">
                                    <?php if (isset($itemList[1][$i])): ?>
                                        <span id='items_<?php print($itemList[1][$i]->getItemID()); ?>' draggable='true'
                                            ondragstart='return drag(event)' class="floatListItem">
                                            <?php print($itemList[1][$i]->getName()); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="col-sm-4">
                                    <?php if (isset($itemList[2][$i])): ?>
                                        <span id='items_<?php print($itemList[2][$i]->getItemID()); ?>' draggable='true'
                                            ondragstart='return drag(event)' class="floatListItem">
                                            <?php print($itemList[2][$i]->getName()); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="pull-right btn-group-vertical">
        <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#addRun">
            Add Item
        </button>
    </div>

    <div class="container">
        <div class="row center">
            <div class="col-sm-6 col-md-offset-3">
                <table class="table table-condensed table-hover table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Run</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <?php
                    $i = 0;
                    foreach ($eventlist as $event) {
                        $i++;
                        ?>
                        <tr>
                            <td><?php print($i); ?></td>
                            <td>
                                <a href="./runs.php?editrun=<?php print($event->getEventID()) ?>">
                                    <?php print($event->getEventName()); ?>
                                </a>
                            </td>
                            <td><?php print($event->getStartDate()); ?></td>
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
<script type="text/javascript">
    <?php if($editing == 1){ ?>
    function DragOver(event) {
        var id = event.dataTransfer.getData("Text");
        var stripped = id.split("_");
        event.preventDefault();
        if (event.target.nodeName == "LI") {
            event.target.parentNode.parentNode.style.background = "#6AA121";
        }
        else if (event.target.nodeName == "UL") {
            event.target.parentNode.style.background = "#6AA121";
        }
        else if (event.target.nodeName == "SPAN") {
            event.target.parentNode.style.background = "#8C2B2B";
        }
        else {
            event.target.style.background = "#8C2B2B";
        }
    }

    function DragLeave(event) {
        event.preventDefault();
        if (event.target.nodeName == "UL") {
            event.target.parentNode.style.background = "";
        }
        else if (event.target.nodeName == "LI") {
            event.target.parentNode.parentNode.style.background = "";
        }
        else if (event.target.nodeName == "IMG") {
            event.target.parentNode.style.background = "";
        }
        else {
            event.target.style.background = "";
        }
    }

    function doDrop(event) {
        var draggedElementID = event.dataTransfer.getData("Text");
        var stripped = draggedElementID.split("_");
        if (event.target.nodeName == "LI") {
            var unorderedList = event.target.parentNode;
        }
        else if (event.target.nodeName == "UL") {
            var unorderedList = event.target;
        }
        else if (event.target.nodeName == "SPAN") {
            console.log(event.target);
            var unorderedList = event.target;
        }
        else {
            var unorderedList = event.target.firstElementChild;
        }

        if (stripped[0] == unorderedList.id) { //check if dragging in the right section
            var allowedToDrop = false;
            if (stripped[0] == "users") {
                var found = userIdInElementChildren("db_users_", stripped[1], unorderedList);
                allowedToDrop = !found;
            }
            else {
                allowedToDrop = true;
            }
            if (allowedToDrop) {
                var li = document.createElement('li');
                li.id = "db_" + stripped[0] + "_id_temp";
                var text = document.getElementById(draggedElementID).innerHTML;
                var item = document.createTextNode(text);
                li.appendChild(item);
                unorderedList.appendChild(li);
                unorderedList.parentNode.style.background = "";
     //           makeRequest("runs.php?editrun=<?php print($run->getID()); ?>&add=" + stripped[0] + "&id=" + stripped[1]);
            }
            else {
                unorderedList.parentNode.style.background = "";
            }
        }
        else if (stripped[0] == 'db' && unorderedList.id == 'bin') {
            //delete on success.
            unorderedList.parentNode.style.background = "";
    //        makeRequest("runs.php?editrun=<?php print($run->getID()); ?>&delete=" + stripped[1] + "&id=" + stripped[2]);
        }
        else if (stripped[0] != unorderedList.id) {
            unorderedList.parentNode.style.background = "";
        }
        event.preventDefault();
    }

    function userIdInElementChildren(pre, id, domElement) {
        for (i = 0; i < domElement.childNodes.length; i += 1) {
            if (domElement.childNodes[i].id == pre + id) {
                return true;
            }
        }
        return false;

    }

    function drag(event) {
        event.target.parentNode.style.background = "#6AA121";
        event.dataTransfer.effectAllowed = 'move';
        event.dataTransfer.setData("Text", event.target.id);
        return true;
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

    function serverResponse(httpRequest) {
        if (httpRequest.readyState === 4) {
            if (httpRequest.status === 200) {
                var result = JSON.parse(httpRequest.responseText);
                if (result['action'] == 'added') {
                    element = document.getElementById("db_" + result["database"] + "_id_temp");
                    element.id = "db_" + result["database"] + "_" + result["id"];
                    element.draggable = true;
                    element.addEventListener("dragstart", function (event) {
                        return drag(event)
                    });
                }
                else if (result['action'] == 'deleted') {
                    element = document.getElementById("db_" + result["database"] + "_" + result["id"]);
                    console.log(result["id"]);
                    element.parentNode.removeChild(element);
                }
            }
            else {
                alert('There was a problem with the request.');
            }
        }
    }
    <?php } ?>
</script>
</body>
</html>