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
        <form action="login.php?action=login" method="post" class="loginForm">
            Username: <input name="name" id="name" type="text" class="inputText float-right" placeholder="Name"><br/>
            Password: <input name="password" id="password" type="password" class="inputText float-right"
                             placeholder="Password"><br/>
            <input type="submit" class="mySubmitButton" value="Log In">
        </form>
    </div>
</div>


<?php include('footer.partial.view.php') ?>
</body>
</html>