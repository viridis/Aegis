<!DOCTYPE html>
<html>
<head>
    <title>LandingsPage</title>
    <?php include('partials/headers.partial.view.php') ?>
</head>
<body role="document">
<?php include('partials/navbar.partial.view.php') ?>
<div class="container" role="main">
    <h3>Profile Management</h3>

    <ul class="nav nav-tabs">
        <li class="active"><a href="#main" data-toggle="tab">Accounts/Characters</a></li>
        <li><a href="#profile" data-toggle="tab">Profile</a></li>
        <li><a href="#settings" data-toggle="tab">Settings</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="main">
            <div style="height: 20px;"></div>
            <div class="container">
                <label for="viewGameAccount">Select game account: </label>
                <select id="viewGameAccount">
                    <option value="" disabled selected>Select game account</option>
                    <?php /** @var User $currentUser */
                    foreach ($currentUser->getGameAccountContainer() as $gameAccount):
                        /** @var GameAccount $gameAccount */
                        ?>
                        <option
                            value="<?php print $gameAccount->getAccountID() ?>"><?php print $gameAccount->getAccountID() ?></option>
                    <?php endforeach ?>
                </select>

                <div id="gameAccount"></div>
            </div>
        </div>
        <div class="tab-pane" id="profile">Under construction</div>
        <div class="tab-pane" id="settings">...</div>
    </div>
</div>
<script>
        $("#viewGameAccount").on("change", function () {
            var selected = $(this).val();
            makeAjaxRequest(selected);
        });


    function makeAjaxRequest(option) {
        $.ajax({
            type: "POST",
            data: { gameAccountID: option},
            url: "profile.php",
            success: function (result) {
                alert("wqe");
                $("#gameAccount").html("<p>$_POST contained: " + result + "</p>"); 
            }
        });
    }
</script>


<?php include('partials/footer.partial.view.php') ?>
</body>
</html>