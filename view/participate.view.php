<!DOCTYPE html>
<html>
<head>
    <title>LandingsPage</title>
    <?php include('partials/headers.partial.view.php') ?>
</head>
<body role="document">
<?php include('partials/navbar.partial.view.php') ?>

<div class="container" role="main">
    <h3>Participate in Events</h3>

    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">
                <a data-toggle="collapse" data-target="#event1" href="#event1" class="collapsed">
                    Collapsible Group Item #1
                </a>
            </h3>
        </div>
        <div id="event1" class="panel-collapse collapse">
            <div class="panel-body">
                Event details
            </div>
        </div>
    </div>


</div>
<?php include('partials/footer.partial.view.php') ?>
</body>
</html>