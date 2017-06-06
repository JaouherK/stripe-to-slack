<?php
/**
 * Copyright (c) 2017.
 */

include_once ('config.php');
require_once('api/init.php');

include_once("StripeToSlackController.php");
$s2 = new StripeToSlackController();

$event_json = $s2->get_incoming_event();

$event_id = $event_json->id;

$events = $s2->get_events($event_json);

$eventInfo = $s2->get_event_info($events[$event_json->type],$event_json->type);

$description = $eventInfo['desc'];
$display_status = $eventInfo['disp'];


if ($display_status == 1) {
    $s2->slack_notification($event_id, $description);
}