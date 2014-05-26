<!DOCTYPE html>
<html>
<head>
    <title>LandingsPage</title>
    <?php include('partials/headers.partial.view.php') ?>
</head>

<body>
<?php include('partials/navbar.partial.view.php') ?>
<div class="container" role="main">

    <div class="modal fade" id="addUser">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Add a user</h4>
                </div>
                <form action="users.php" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" id="addUser" name="addUser" placeholder="Username">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="Add User">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUser">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Edit a user</h4>
                </div>
                <form id="submitEditUser" action="users.php" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" class="form-control" id="editUserID" name="editUserID"
                                   placeholder="User ID">
                            <label class="control-label">Username: </label>
                            <input type="text" class="form-control" id="editUserName" name="editUserName"
                                   placeholder="User Name">
                            <label class="control-label">Mailname: </label>
                            <input type="text" class="form-control" id="editUserMailName" name="editUserMailName"
                                   placeholder="User Mail Name">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="Edit User">
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
        <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#addUser">
            Add User
        </button>
    </div>

    <div class="container">
        <div class="row center">
            <div class="col-sm-4 col-md-offset-4">
                <table class="table table-condensed table-hover table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Nickname</th>
                        <th>Mailname</th>
                        <th></th>
                    </tr>
                    </thead>
                    <?php
                    $i = 0;
                    foreach ($userContainer as $user) {
                        $i++;
                        ?>
                        <tr>
                            <td><?php print($i); ?></td>
                            <td><?php print($user->getName()); ?></td>
                            <td><?php print($user->getMailName()); ?></td>
                            <td>
                                <span class="glyphicon glyphicon-pencil hand"
                                      onclick="editUser(<?php print($user->getId()); ?>);" data-toggle="modal"
                                      data-target="#editUser"></span>
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
<script>
    window.onload = function () {
        document.getElementById("addUserButton").onclick = function (event) {
            popup('popAddUser', 'block');
            setFocus('addUser');
        }
        document.getElementById("popupWrapper").onclick = function (event) {
            popup('popAddUser', 'none');
            popup('popEditUser', 'none');
        }

    }
    window.onresize = initializePage;

    //close popup boxes using ESC key.
    document.onkeydown = function (event) {
        event = event || window.event;
        if (event.keyCode == 27) {
            popup('popAddUser', 'none');
        }
    }

    function editUser(id) {
        makeRequest("users.php?editUser=" + id);
    }

    function serverResponse(httpRequest) {
        if (httpRequest.readyState === 4) {
            if (httpRequest.status === 200) {
                result = JSON.parse(httpRequest.responseText);
                if (result['action'] == "requestUser") {
                    document.getElementById("editUserID").value = result['user']['id'];
                    document.getElementById("editUserName").value = result['user']['name'];
                    document.getElementById("editUserMailName").value = result['user']['mailName'];
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