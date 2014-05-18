<!DOCTYPE html>
<html>
<head>
    <title>LandingsPage</title>
    <?php include('partials/headers.partial.view.php') ?>
</head>

<body>
<?php include('partials/navbar.partial.view.php') ?>
    <div class="container" role="main">

        <?php if (isset($notification)): ?>
            <div class="alert alert-<?php print($notification['type']); ?> alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <p><strong><?php print($notification['title']); ?></strong> - <?php print($notification['message']); ?></p>
            </div>
        <?php endif; ?>

        <div class="jumbotron">
            <form action="login.php?action=login" method="post" class="form-horizontal" role="form">
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" id="name" name="name"
                               placeholder="Enter Username">
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-sm-2 control-label">password</label>
                    <div class="col-sm-2">
                        <input type="password" class="form-control" id="password" name="password"
                               placeholder="Enter password">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-2">
                        <button type="submit" class="btn btn-success">Sign in</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


<?php include('partials/footer.partial.view.php') ?>
</body>
</html>