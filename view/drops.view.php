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
    <h3>Participate in Events</h3>

    <div class="panel-group" id="eventPanels">
        <?php foreach ($eventContainer as $event) :
            /** @var Event $event */
            $eventDate = strtotime($event->getCompleteDate());
            $displayEventDate = date('d M gA', $eventDate + ($currentUser->getGMT() * 3600));
            $hoursToEvent = round((time() - $eventDate) / 3600, 1);
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <a data-toggle="collapse" data-parent="#eventPanels"
                           href="#event<?php print $event->getEventID(); ?>" class="collapsed">
                            <?php print $event->getEventID() . ". " . $event->getEventName() . " , " . $displayEventDate . " , " . $hoursToEvent . " hour(s) ago" ?>
                        </a>
                    </h3>
                </div>
                <div id="event<?php print $event->getEventID(); ?>" class="panel-collapse collapse">
                    <div class="panel-body">
                        <table class="table">

                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>


    <?php include('partials/footer.partial.view.php') ?>
</body>
</html>