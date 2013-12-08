<!DOCTYPE html>
<html>
<head>
    <title>LandingsPage</title>
    <?php include('headers.partial.view.php') ?>
    <script>
        window.onload = function () {
            initializePage();
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
                        popup('popEditUser', 'block');
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
</head>

<body>
<?php include('navbar.partial.view.php') ?>

<div class="content">
    <?php if($sessionUser->getPermission() >= 10): ?>
    <div class="popupWrapper" id="popupWrapper"></div>
    <div class="popup" id="popAddUser">
        <form action="users.php" method="post">
            <input name="addUser" id="addUser" class="inputText" type="text" placeholder="Username">
            <input type="submit" class="mySubmitButton" value="Add User">
        </form>
    </div>
    <div class="popup" id="popEditUser">
        <form id="submitEditUser" action="users.php" method="post">
            <input name="editUserID" id="editUserID" type="hidden" class="inputText" placeholder="User ID">
            <input name="editUserName" id="editUserName" type="text" class="inputText" placeholder="User Name">
            <input name="editUserMailName" id="editUserMailName" type="text" class="inputText"
                   placeholder="User Mail Name">
            <input type="submit" class="mySubmitButton" value="Edit User">
        </form>
    </div>
    <?php endif; ?>
    <?php if (isset($notification)): ?>
        <br/>
        <div class="<?php print($notification['type']); ?>">
            <h1><?php print($notification['message']); ?></h1>
        </div>
        <br/>
    <?php endif; ?>
    <?php if($sessionUser->getPermission() >= 10): ?>
    <div class="buttonContainer">
        <input id="addUserButton" type="submit" class="myButton" value="Add User">
    </div>
    <?php endif; ?>
    <div class="featured-node" style="margin-top: 20px;">
        <table>
            <tr>
                <td style="width: 20px; padding: 2px 5px;"></td>
                <td style="padding: 2px 5px;">Nickname</td>
                <td style="padding: 2px 5px;">Mailname</td>
                <td></td>
            </tr>
            <?php
            $i = 0;
            foreach ($userlist as $user) {
                $i++;
                ?>
                <tr>
                    <td style="width: 20px; padding: 2px 5px;"><?php print($i); ?></td>
                    <td style="padding: 2px 5px;"><?php print($user->getName()); ?></td>
                    <td style="padding: 2px 5px;"><?php print($user->getMailName()); ?></td>
                    <td>
                        <?php if($sessionUser->getPermission() >= 10): ?>
                        <img src="../assets/edit_icon.png" class="editIcon"
                             onclick="editUser(<?php print($user->getId()); ?>);">
                        <?php endif; ?>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
</div>


<?php include('footer.partial.view.php') ?>
</body>
</html>