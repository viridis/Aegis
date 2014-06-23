<!DOCTYPE html>
<html>
<head>
    <title>LandingsPage</title>
    <?php include('partials/headers.partial.view.php'); ?>
</head>
<body role="document">
<?php include('partials/navbar.partial.view.php'); ?>

<div class="container" role="main">

    <?php if (isset($notification)): ?>
        <div class="alert alert-<?php print($notification['type']); ?> alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <p><strong><?php print($notification['title']); ?></strong> - <?php print($notification['message']); ?></p>
        </div>
    <?php endif; ?>

    <div class="jumbotron">
        <form action="settings.php?action=edit" method="post" class="form-horizontal" role="form">
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Username</label>

                <div class="col-sm-4">
                    <?php print($user->getUserLogin()); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="mailChar" class="col-sm-2 control-label">Mail Name (ingame)</label>

                <div class="col-sm-4">
                    <input type="text" class="form-control" id="mailChar" name="mailChar"
                           placeholder="Ingame Mailname" value="<?php print($user->getMailChar()) ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">E-mail</label>

                <div class="col-sm-4">
                    <input type="text" class="form-control" id="email" name="email"
                           placeholder="E-mail Address" value="<?php print($user->getEmail()) ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="GMT" class="col-sm-2 control-label">Timezone GMT</label>

                <div class="col-sm-2">
                    <select class="form-control" id="GMT" name="GMT">
                        <?php for ($i = -12;
                                   $i <= 13;
                                   $i++) :
                            print "<option value=" . $i;
                            if ($user->getGMT() == $i) {
                                print " selected";
                            }
                            print ">" . $i . "</option>";
                        endfor; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-2">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </div>
        </form>
    </div>

    <div class="jumbotron">
        <form action="settings.php?action=password" method="post" class="form-horizontal" role="form">
            <div class="form-group">
                <label for="oldpassword" class="col-sm-2 control-label">Old Password</label>

                <div class="col-sm-4">
                    <input type="password" class="form-control" id="oldpassword" name="oldpassword"
                           placeholder="Old Password">
                </div>
            </div>
            <div class="form-group">
                <label for="forumname" class="col-sm-2 control-label">New Password</label>

                <div class="col-sm-4">
                    <input type="password" class="form-control" id="newpassword" name="newpassword"
                           placeholder="New Password">
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">Confirm Password</label>

                <div class="col-sm-4">
                    <input type="password" class="form-control" id="confirmpassword" name="confirmpassword"
                           placeholder="Confirm Password">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-2">
                    <button type="submit" class="btn btn-success">Update Password</button>
                </div>
            </div>
        </form>
    </div>

</div>


<?php include('partials/footer.partial.view.php') ?>
</body>
</html>