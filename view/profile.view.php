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
            <div class="container" style="padding-left:0px">
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

                <br/><br/>

                <div class="container" id="gameAccount" style="padding-left:0px">
                    <table id="characterTable" class="table table-condensed table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>
                                Char ID
                            </th>
                            <th>
                                Char Name
                            </th>
                            <th>
                                Char Class
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
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
                processJSONGameAccount(result);
            }
        });
    }

    function processJSONGameAccount(result) {
        var gameAccount = result.split('|');
        var accountObject = jQuery.parseJSON(gameAccount[0]);
//        $("#gameAccountID").html(accountObject.accountID);
        $("#characterTable").find("tr:gt(0)").remove();
        for (var i = 1; i < gameAccount.length; i++) {
            var character = jQuery.parseJSON(gameAccount[i]);
            $('#characterTable tr:last').after('<tr><td>' + character.charID + '</td><td>' + character.charName + '</td><td>' + character.charClassID + '</tr>');
        }
    }
</script>


<?php include('partials/footer.partial.view.php') ?>
</body>
</html>