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
        <h1>Aegis</h1>

        <p>Aegis Inventory System - Where you literally play the game and Drew pays you to do so.
            This header is usually nice when there is quite a bit of text. So let's make some text.</p>

        <p><a href="#" class="btn btn-primary btn-lg" role="button">Check it out &raquo;</a></p>
    </div>

</div>
<?php include('partials/footer.partial.view.php'); ?>
</body>
</html>