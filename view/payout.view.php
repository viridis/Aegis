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
        <table>
            <tr>
                <td style="width: 20px; padding: 2px 5px;"></td>
                <td style="padding: 2px 5px; font-weight: bold;">Name</td>
                <td style="padding: 2px 5px; font-weight: bold;">Mailname</td>
                <td style="padding: 2px 5px; font-weight: bold">To Be Paid Out</td>
                <td></td>
            </tr>
            <?php
            $i = 0;
            foreach ($payoutList as $payOut) {
                if ($payOut[1] != 0) {
                    $i++;
                    ?>
                    <tr>
                        <td style="width: 20px; padding: 2px 5px;"><?php print($i); ?></td>
                        <td style="padding: 2px 5px;"><?php print($payOut[0]->getName()); ?></td>
                        <td style="padding: 2px 5px;"><?php print($payOut[0]->getMailName()); ?></td>
                        <td style="padding: 2px 5px;text-align: right;font-size: 14px; font-weight: bold;"><?php print(number_format($payOut[1])); ?></td>
                        <?php if ($_SESSION["userID"]): ?>
                            <td style="vertical-align: top;">
                                <form action="payout.php?action=payout" method="post">
                                    <input name="userId" id="userId" type="hidden"
                                           value="<?php print($payOut[0]->getUserID()); ?>">
                                    <input type="submit" class="mySubmitButton" value="Pay Out">
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php
                }
            }
            ?>
        </table>
    </div>

</div>


<?php include('footer.partial.view.php') ?>
</body>
</html>