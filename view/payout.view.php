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
    <div style="height: 20px;"></div>

    <div class="container">
        <div class="row center">
            <div class="col-sm-6 col-md-offset-3">
                <table class="table table-condensed table-hover table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Mailname</th>
                        <th>Payout</th>
                        <?php if (isset($allowedToPayOut) && $allowedToPayOut): ?>
                            <th></th>
                        <?php endif; ?>
                    </tr>
                    </thead>
                    <?php
                    $i = 0;
                    foreach ($userContainer as $user) {
                        /** @var User $user */
                        $i++;
                        ?>
                        <tr>
                            <td><?php print($i); ?></td>
                            <td><?php print($user->getUserLogin()); ?></td>
                            <td><?php print($user->getMailChar()); ?></td>
                            <td><?php print(number_format($user->getPayout())); ?></td>
                            <?php if (isset($allowedToPayOut) && $allowedToPayOut): ?>
                                <td>
                                    <form action="payout.php?action=payout" method="post">
                                        <input name="userId" id="userId" type="hidden"
                                               value="<?php print($user->getUserID()); ?>">
                                        <button type="submit" class="btn btn-xs btn-success">Pay Out</button>
                                    </form>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>

</div>

<?php include('partials/footer.partial.view.php'); ?>
</body>
</html>