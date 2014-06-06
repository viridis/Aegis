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
        <h3>Register an account</h3>
        <form class="form-horizontal" action="register.php" method="post" role="Register User">
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Username</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Username"
                           value="<?php echo !empty($_POST['name']) ? $_POST['name'] : '' ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Aegis Username</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="aegisName" name="aegisName" placeholder="Enter Username"
                        value="<?php echo !empty($_POST['aegisName']) ? $_POST['aegisName'] : '' ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-sm-2 control-label">Password</label>
                <div class="col-sm-2">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-sm-2 control-label">Retype Password</label>
                <div class="col-sm-2">
                    <input type="password" class="form-control" id="retypePassword" name="retypePassword" placeholder="Enter password">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-2">
                    <button type="submit" class="btn btn-success">Sign Up</button>
                </div>
            </div>
        </form>
    </div>

</div>
<?php include('partials/footer.partial.view.php'); ?>
</body>
</html>