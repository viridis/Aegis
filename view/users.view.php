<!DOCTYPE html>
<html>
<head>
    <title>LandingsPage</title>
    <?php include('partials/headers.partial.view.php') ?>
</head>

<body>
<?php include('partials/navbar.partial.view.php') ?>
<div class="container" role="main">
    <h3>Manage Users</h3>
    Note: click to edit, enter to save
    <table class="table table-bordered" id="eventTemplateTable">
        <thead>
        <tr>
            <th>Username</th>
            <th>Role Level</th>
            <th>GMT</th>
            <th>Email</th>
            <th>Mail Char</th>
            <th>Password</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($userContainer as $user) :
            /** @var User $user */ ?>
            <tr>
                <td><?php print $user->getUserLogin();?></td>
                <td class="edit" id="roleLevel_<?php print $user->getUserID();?>"><?php print $user->getRoleLevel();?></td>
                <td class="edit" id="GMT_<?php print $user->getUserID();?>"><?php print $user->getGMT(); ?></td>
                <td class="edit" id="email_<?php print $user->getUserID();?>"><?php print $user->getEmail(); ?></td>
                <td class="edit" id="mailChar<?php print $user->getUserID();?>"><?php print $user->getMailChar(); ?></td>
                <td class="edit" id="password_<?php print $user->getUserID();?>">Click to Change</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="container col-sm-6">
        <form class="form-horizontal">
            <div class="form-group">
                <label for="newUser" class="control-label col-sm-4">New User</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="newUser" name="newUser"
                           placeholder="User Login">
                </div>
                <div class="col-sm-2">
                    <input type="button" class="btn btn-primary" value="Create"
                           id="createUser" onclick="createUser(); return false;">
                </div>
            </div>
        </form>
    </div>
</div>



<?php include('partials/footer.partial.view.php') ?>

<script>
    $(document).ready(function () {
        $('.edit').editable('manageEventTemplate.php', {
            id: 'id',
            name: 'value'
        });
    });

    function createUser() {
        var newUserLogin = $('#newUser').val();
        makeAddAjaxRequest(newUserLogin);
    }

    function makeAddAjaxRequest(newUserLogin) {

        $.ajax({
            type: "POST",
            data: { newUserLogin: newUserLogin},
            url: "users.php",
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