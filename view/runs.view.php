<!DOCTYPE html>
<html>
<head>
    <title>LandingsPage</title>
    <?php include('partials/headers.partial.view.php'); ?>
</head>
<body role="document">
<?php include('partials/navbar.partial.view.php'); ?>

<div class="container" role="main">

<div class="modal fade" id="addRun">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Create Event</h4>
            </div>
            <form class="form-horizontal" action="runs.php?addRun=1" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="eventName" class="col-sm-4 control-label">Event Name</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="eventName" name="eventName"
                                   placeholder="Event Name">
                        </div>
                        <label for="eventType" class="col-sm-4 control-label">Event Type</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="eventType" name="eventType"
                                   placeholder="Event Type">
                        </div>
                        <label for="startDate" class="col-sm-4 control-label">Date (mm/dd/yyyy)</label>

                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="startDate" name="startDate"
                                   placeholder="Date">
                        </div>
                        <label for="startTime" class="col-sm-4 control-label">Time (hh:mm AM/PM)</label>

                        <div class="col-sm-8">
                            <input type="time" class="form-control" id="startTime" name="startTime">
                        </div>
                        <label for="numSlot" class="col-sm-4 control-label">Num Slots</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="numSlot" name="numSlot" value="12">
                        </div>
                        <label for="recurringEvent" class="col-sm-4 control-label">Recurring?</label>
                        <input type="checkbox" id="recurringEvent" name="recurringEvent" value="1">

                        <div style="height: 10px;"></div>

                        <div class="recurring" id="recur" style="display: none;">
                            <label for="dayOfWeek" class="col-sm-4 control-label">Day Of Week</label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="dayOfWeek" name="dayOfWeek"
                                       placeholder="Day Of Week (1-7)">
                            </div>
                            <label for="hourOfDay" class="col-sm-4 control-label">Hour Of Day</label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="hourOfDay" name="hourOfDay"
                                       placeholder="Hour Of Day (1-24)">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Create Event">
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
<div style="height: 20px;"></div>

<?php if ($editing == 1): ?>
    <div class="jumbotron">
        <div class="pull-left trashIcon" id="bin" ondragover="return DragOver(event)"
             ondragleave="return DragLeave(event)" ondrop="return doDrop(event)">
            <span id="bin" class="glyphicon glyphicon-trash"></span>
        </div>

        <h3>
            <?php
            print($event->getEventName());
            print(" -- ");
            print($event->getStartDate());
            ?>
        </h3>
        <br>

        <div class="container">
            <div class="row">

                <div class="col-sm-2">
                    <h4>Participants</h4>
                    <ul class="userList" id="users" class="dropable_lists" ondragover="return DragOver(event)"
                        ondragleave="return DragLeave(event)" ondrop="return doDrop(event)">
                        <?php
                        foreach ($slotList as $slot) :
                            /** @var Slot $slot */
                            ?>
                            <li id="db_users_<?php print($slot->getTakenUserID()); ?>" draggable='true'
                                ondragstart="return drag(event)">
                                <?php
                                if ($slot->getTakenUserID() > 0)
                                    print($slot->getUserLogin());
                                else
                                    print("vacant"); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="col-sm-10">
                    <?php for ($i = 0; $i < count($userContainer); $i++) : ?>
                        <div class="row">
                            <div class="col-sm-4">
                                <?php if (isset($userContainer[0][$i])): ?>
                                    <span id='users_<?php print($userContainer[0][$i]->getUserID()); ?>'
                                          draggable='true'
                                          ondragstart='return drag(event)' class="floatListItem">
                                    <?php print($userContainer[0][$i]->getUserLogin()); ?>
                                </span>
                                <?php endif; ?>
                            </div>
                            <div class="col-sm-4">
                                <?php if (isset($userContainer[1][$i])): ?>
                                    <span id='users_<?php print($userContainer[1][$i]->getUserID()); ?>'
                                          draggable='true'
                                          ondragstart='return drag(event)' class="floatListItem">
                                    <?php print($userContainer[1][$i]->getUserLogin()); ?>
                                </span>
                                <?php endif; ?>
                            </div>
                            <div class="col-sm-4">
                                <?php if (isset($userContainer[2][$i])): ?>
                                    <span id='users_<?php print($userContainer[2][$i]->getUserID()); ?>'
                                          draggable='true'
                                          ondragstart='return drag(event)' class="floatListItem">
                                    <?php print($userContainer[2][$i]->getUserLogin()); ?>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-sm-2">
                    <h4>Drops</h4>
                    <ul class="itemList" id="items" class="dropable_lists" ondragover="return DragOver(event)"
                        ondragleave="return DragLeave(event)" ondrop="return doDrop(event)">
                        <?php
                        foreach ($event->getDropList() as $drop):
                            /** @var Drop $drop */
                            ?>
                            <li id="db_items_<?php print($drop->getDropID()); ?>" draggable='true'
                                ondragstart='return drag(event)'>
                                <?php
                                print($drop->getItemName()); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="col-sm-10">
                    <?php for ($i = 0; $i < count($itemList[0]); $i++) : ?>
                        <div class="row">
                            <div class="col-sm-4">
                                <?php if (isset($itemList[0][$i])): ?>
                                    <span id='items_<?php print($itemList[0][$i]->getItemID()); ?>' draggable='true'
                                          ondragstart='return drag(event)' class="floatListItem">
                                            <?php print($itemList[0][$i]->getName()); ?>
                                        </span>
                                <?php endif; ?>
                            </div>
                            <div class="col-sm-4">
                                <?php if (isset($itemList[1][$i])): ?>
                                    <span id='items_<?php print($itemList[1][$i]->getItemID()); ?>' draggable='true'
                                          ondragstart='return drag(event)' class="floatListItem">
                                            <?php print($itemList[1][$i]->getName()); ?>
                                        </span>
                                <?php endif; ?>
                            </div>
                            <div class="col-sm-4">
                                <?php if (isset($itemList[2][$i])): ?>
                                    <span id='items_<?php print($itemList[2][$i]->getItemID()); ?>' draggable='true'
                                          ondragstart='return drag(event)' class="floatListItem">
                                            <?php print($itemList[2][$i]->getName()); ?>
                                        </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="pull-right btn-group-vertical">
    <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#addRun">
        Create Event
    </button>
</div>

<div class="container">
    <div class="row center">
        <div class="col-sm-6 col-md-offset-3">
            <table class="table table-condensed table-hover table-striped table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Run</th>
                    <th>Date</th>
                </tr>
                </thead>
                <?php
                $i = 0;
                foreach ($eventContainer as $event) {
                    /** @var Event $event */
                    $i++;
                    ?>
                    <tr>
                        <td><?php print($i); ?></td>
                        <td>
                            <a href="./runs.php?editrun=<?php print($event->getEventID()) ?>">
                                <?php print($event->getEventName()); ?>
                            </a>
                        </td>
                        <td><?php print($event->getStartDate()); ?></td>
                    </tr>
                <?php
                }
                ?>
            </table>
        </div>
    </div>
</div>
</div>


<?php include('partials/footer.partial.view.php') ?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#recurringEvent").click(function () {
            if ($("#recurringEvent").is(":checked")) {
                $("#recur").show("fast");
            }
            else {
                $("#recur").hide("fast");
            }
        });
    });
</script>
</body>
</html>