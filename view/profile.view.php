<!DOCTYPE html>
<html>
<head>
    <title>LandingsPage</title>
    <?php include('partials/headers.partial.view.php') ?>
</head>
<body role="document">
<?php include('partials/navbar.partial.view.php') ?>


<div class="modal fade" id="addGameAccount">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Create Game Account</h4>
            </div>
            <form class="form-horizontal" action="profile.php?addGameAccount=1" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="accountName" class="col-sm-4 control-label">Account Name</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="accountName" name="accountName"
                                   placeholder="Account Name">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Create Game Account">
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addCharacter">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Create Character</h4>
            </div>
            <form class="form-horizontal" action="profile.php?addCharacter=1" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" id="accountID" name="accountID" value=""/>
                        <label for="charName" class="col-sm-4 control-label">Character Name</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="charName" name="charName"
                                   placeholder="Character Name">
                        </div>
                        <label for="charClassID" class="col-sm-4 control-label">Character Class</label>

                        <div class="col-sm-8">
                            <select class="form-control" id="charClassID" name="charClassID">
                                <?php foreach ($charClassContainer as $charClass) :
                                    /** @var CharClass $charClass */
                                    ?>
                                    <option
                                        value="<?php print $charClass->getCharClassID() ?>"><?php print $charClass->getCharClassName() ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Create Character">
                </div>
            </form>
        </div>
    </div>
</div>

<?php if (isset($notification)): ?>
    <div class="alert alert-<?php print($notification['type']); ?> alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <p><strong><?php print($notification['title']); ?></strong> - <?php print($notification['message']); ?></p>
    </div>
<?php endif; ?>

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
                <div class="row col-sm-8">
                    <label for="viewGameAccount">Select game account: </label>
                    <select id="viewGameAccount">
                        <option value="" disabled selected>Select game account</option>
                        <?php /** @var User $currentUser */
                        foreach ($currentUser->getGameAccountContainer() as $gameAccount):
                            /** @var GameAccount $gameAccount */
                            ?>
                            <option
                                value="<?php print $gameAccount->getAccountID() ?>"><?php print $gameAccount->getGameAccountName() ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="row col-sm-4">
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addGameAccount">
                        Create Game Account
                    </button>
                </div>
                <br/><br/>

                <div class="container" id="gameAccount" style="padding-left:0px" hidden>
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
                    <div>
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addCharacter">
                            Create Character
                        </button>
                    </div>
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
        $("#gameAccount").show();
        $("#gameAccountID").html(accountObject.accountID);
        $("#characterTable").find("tr:gt(0)").remove();
        for (var i = 1; i < gameAccount.length; i++) {
            var character = jQuery.parseJSON(gameAccount[i]);
            $('#characterTable tr:last').after('<tr><td>' + character.charID + '</td><td>' + character.charName + '</td><td>' + character.charClassName + '</tr>');
        }
        $("#accountID").val(accountObject.accountID + '');

    }
</script>


<?php include('partials/footer.partial.view.php') ?>
</body>
</html>