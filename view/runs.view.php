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
                        <label for="eventTypeID" class="col-sm-4 control-label">Event Type</label>

                        <div class="col-sm-8">
                            <select class="form-control" id="eventTypeID" name="eventTypeID">
                                <?php foreach ($eventTypeContainer as $eventType) :
                                    /** @var EventType $eventType */
                                    ?>
                                    <option
                                        value="<?php print $eventType->getEventTypeID() ?>"><?php print $eventType->getEventName() ?></option>
                                <?php endforeach ?>
                            </select>
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
                        <label for="recurringEvent" class="col-sm-4 control-label">Recurring?</label>
                        <input type="checkbox" id="recurringEvent" name="recurringEvent" value="1">

                        <div style="height: 10px;"></div>

                        <div id="recur" style="display: none;">
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
        <h3>
            <?php
            /** @var Event $eventEditing */
            print("Editing event (Event ID: ");
            print($eventEditing->getEventID());
            print(" - ");
            print($eventEditing->getEventName());
            print(")");
            ?>
        </h3>
        <br>

        <div class="container">
            <form class="form-horizontal" action="runs.php?editRun=<?php print $eventEditing->getEventID() ?>"
                  method="post">
                <div class="form-group">
                    <label for="eventTypeEdit" class="col-sm-4 control-label">Event Type</label>

                    <div class="col-sm-8">
                        <select class="form-control" id="eventTypeID" name="eventTypeID">
                            <?php foreach ($eventTypeContainer as $eventType) :
                                /** @var EventType $eventType */
                                ?>
                                <option <?php if ($eventEditing->getEventTypeID() == $eventType->getEventTypeID()) print "selected=\"selected\"" ?>
                                    value="<?php print $eventType->getEventTypeID() ?>"><?php print $eventType->getEventName() ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <label for="startDateEdit" class="col-sm-4 control-label">Date (mm/dd/yyyy)</label>

                    <div class="col-sm-8">
                        <input type="date" class="form-control" id="startDateEdit" name="startDate"
                               placeholder="Date" value="<?php print explode(' ', $eventEditing->getStartDate())[0] ?>">
                    </div>
                    <label for="startTimeEdit" class="col-sm-4 control-label">Time (hh:mm AM/PM)</label>

                    <div class="col-sm-8">
                        <input type="time" class="form-control" id="startTimeEdit" name="startTime"
                               value="<?php print explode(' ', $eventEditing->getStartDate())[1] ?>">
                    </div>

                    <?php $slotNum = 0;
                    foreach ($eventEditing->getSlotList() as $slot) :
                        $slotNum++;
                        /** @var Slot $slot */
                        ?>
                        <label for="slotEdit_<?php print $slot->getSlotID() ?>"
                               class="col-sm-4 control-label">Slot <?php print $slotNum ?> Class</label>

                        <div class="col-sm-8">
                            <select class="form-control" id="slotEdit_<?php print $slot->getSlotID() ?>"
                                    name="slotEdit_<?php print $slot->getSlotID() ?>">
                                <?php foreach ($slotClassArray as $slotClass) :
                                    /** @var SlotClass $slotClass */
                                    ?>
                                    <option <?php if ($slot->getSlotClassID() == $slotClass->getSlotClassID()) print "selected=\"selected\"" ?>
                                        value="<?php print $slotClass->getSlotClassID() ?>"><?php print $slotClass->getSlotClassName() ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    <?php endforeach; ?>
                    <div><h5 style="padding-left: 200px">note: set class to -1 disable/reserve</h5></div>
                    <label for="recurringEventEdit" class="col-sm-4 control-label">Recurring?</label>
                    <input type="checkbox" id="recurringEventEdit" name="recurringEvent"
                           value="1" <?php if ($eventEditing->isRecurringEvent()) echo 'checked' ?> >

                    <div style="height: 10px;"></div>

                    <div
                        id="recurEdit" <?php if (!$eventEditing->isRecurringEvent()) print 'style="display: none;"' ?> >
                        <label for="dayOfWeekEdit" class="col-sm-4 control-label">Day Of Week</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="dayOfWeekEdit" name="dayOfWeek"
                                   placeholder="Day Of Week (1-7)"
                                   value="<?php print $eventEditing->getDayOfWeek() ?>">
                        </div>
                        <label for="hourOfDayEdit" class="col-sm-4 control-label">Hour Of Day</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="hourOfDayEdit" name="hourOfDay"
                                   placeholder="Hour Of Day (1-24)"
                                   value="<?php print $eventEditing->getHourOfDay() ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10">
                        <input class="btn btn-primary" type="button" onclick="window.location.replace('runs.php')"
                               value="Cancel"/>
                    </div>
                    <div class="col-sm-2">
                        <input type="submit" class="btn btn-primary" value="Save Changes">
                    </div>
                </div>
            </form>
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
        <div class="col-sm-8 col-md-offset-2">
            <table class="table table-condensed table-hover table-striped table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Event Type</th>
                    <th>Date (yyyy/mm/dd hh:mm:ss)</th>
                    <th>Status</th>
                    <?php if (isset($allowedToCloseEvent) && $allowedToCloseEvent): ?>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>
                </thead>
                <?php
                $i = 0;
                foreach ($eventContainer as $event) {
                    /** @var Event $event */
                    ?>
                    <tr>
                        <td><?php print($event->getEventID()); ?></td>
                        <td>
                            <?php if ($event->getEventState() == 0) : ?>
                                <a href="./runs.php?editRun=<?php print($event->getEventID()) ?>">
                                    <?php print($event->getEventName()); ?>
                                </a>
                            <?php
                            else : print($event->getEventName());
                            endif ?>
                        </td>
                        <td><?php print($event->getStartDate()); ?></td>
                        <?php if ($event->getEventState() == 1) : ?>

                            <?php print('<td style="background-color:red"> Closed</td>');
                        else : print('<td style="background-color:green"> Open</td>');
                        endif ?>

                        <?php if (isset($allowedToCloseEvent) && $allowedToCloseEvent):
                            if ($event->getEventState() == 0) : ?>
                                <td>
                                    <a class="btn btn-xs btn-warning"
                                       id="close_event_<?php print $event->getEventID() . '_' . $i ?>">Close this
                                        event</a>
                                </td>
                            <?php endif;
                            if ($event->getEventState() == 1) : ?>
                                <td>
                                    <a class="btn btn-xs btn-warning"
                                       id="open_event_<?php print $event->getEventID() . '_' . $i ?>">Open this
                                        event</a>
                                </td>
                            <?php endif ?>
                        <?php endif ?>
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
        $("#recurringEventEdit").click(function () {
            if ($("#recurringEventEdit").is(":checked")) {
                $("#recurEdit").show("fast");
            }
            else {
                $("#recurEdit").hide("fast");
            }
        });

        $(".btn-warning").click(function (event) {
            event.preventDefault();
            var id = event.target.id;
            var result = id.split('_');

            $('body').prepend('<div id="dataConfirmModal' + result[2] + '" class="modal fade">' +
                '<div class="modal-dialog modal-sm">' +
                '<div class ="modal-content">' +
                '<div class="modal-body"> <h3 id="dataConfirmLabel">Confirm ' + result[0] + ' ' + result[1] + ' ' + result[3] + '?</h3></div>' +
                '<div class="modal-footer">' +
                '<div class="col-sm-3">' +
                '<button class="btn" class="close" data-dismiss="modal" aria-hidden="true">No</button>' +
                '</div>' + '<div class="col-sm-9">' +
                '<form action="runs.php?action=' + result[0] + 'Event" method="post">' +
                '<input name="eventID" id="eventID" type="hidden" value="' + result[2] + '">' +
                '<button id="openEventButton' + result[2] + '" type="submit" class="btn">Yes</button>' +
                '</form>' +
                '</div></div></div></div>');
            $('#dataConfirmModal' + result[2]).modal({show: true});
        })
    });

</script>
</body>
</html>