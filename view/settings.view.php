<!DOCTYPE html>
<html>
<head>
    <title>LandingsPage</title>
    <?php include('headers.partial.view.php') ?>
    <script>
        window.onload = function () {
            initializePage();
        }

        window.onresize = initializePage;
    </script>
</head>

<body>
<?php include('navbar.partial.view.php') ?>

<div class="content">
    <?php if (isset($notification)): ?>
        <br/>
        <div class="<?php print($notification['type']); ?>">
            <h1><?php print($notification['message']); ?></h1>
        </div>
        <br/>
    <?php endif; ?>

    <div class="featured-node" style="margin-top: 20px;">
        <form action="settings.php?action=edit" method="post" class="settingsForm">
            <span class="settingsFormItem">User Name: </span>
                <?php print($user->getName()); ?><br/>
            <span class="settingsFormItem">Mail Name (ingame): </span>
                <input name="mailname" id="mailname" type="text" class="inputText float-right" placeholder="Ingame Mailname" value="<?php print($user->getMailName()) ?>"><br/>
            <span class="settingsFormItem">Forum Name: </span>
                <input name="forumname" id="forumname" type="text" class="inputText float-right" placeholder="Forum Name" value="<?php print($user->getForumName()) ?>"><br/>
            <span class="settingsFormItem">Email Address: </span>
                <input name="email" id="email" type="text" class="inputText float-right" placeholder="Email Address" value="<?php print($user->getEmail()) ?>"><br/>
            <input type="submit" class="mySubmitButton" value="Update">
        </form>
    </div>

    <div style="clear:both;"></div>
    <div class="featured-node" style="margin-top: 20px;">
        <form action="settings.php?action=password" method="post" class="settingsForm">
            <span class="settingsFormItem">Old Password: </span>
            <input name="oldpassword" id="oldpassword" type="password" class="inputText float-right" placeholder="Old Password"><br/>
            <span class="settingsFormItem">New Password: </span>
            <input name="newpassword" id="newpassword" type="password" class="inputText float-right" placeholder="New Password"><br/>
            <span class="settingsFormItem">Confirm Password: </span>
            <input name="confirmpassword" id="confirmpassword" type="password" class="inputText float-right" placeholder="Confirm Password"><br/>
            <input type="submit" class="mySubmitButton" value="Update Password">
        </form>
    </div>

</div>


<?php include('footer.partial.view.php') ?>
</body>
</html>