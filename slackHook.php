<?php
require_once('api/init.php');
\Stripe\Stripe::setApiKey('STRIPE-API-KEY');
$input = @file_get_contents("php://input");
$event_json = json_decode($input);

$event_id = $event_json->id;

$events = array(
    "charge.succeeded" => array("lib" => "Customer successfully payed the amount", "disp" => 1),
    "charge.failed" => array("lib" => "Customer's card is declined", "disp" => 1),
    "charge.refunded" => array("lib" => "Customer successfully purchases and then requests a refund", "disp" => 1),
    "charge.captured" => array("lib" => "Create a charge without capturing it, capture later", "disp" => 1),
    "charge.updated" => array("lib" => "Charge then modify description", "disp" => 1),
    "charge.dispute.created" => array("lib" => "Customer disputes a charge", "disp" => 1),
    "charge.dispute.updated" => array("lib" => "Customer disputes a charge, you upload evidence", "disp" => 1),
    "charge.dispute.closed" => array("lib" => "Customer disputes a charge, you upload evidence", "disp" => 1),
    "customer.created" => array("lib" => "Customer created" . $event_json->data->object->id, "disp" => 1),
    "customer.updated" => array("lib" => "Customer " . $event_json->data->object->id . " details were updated", "disp" => 0),
    "customer.source.created" => array("lib" => "Customer " . $event_json->data->object->customer . " added a new " . $event_json->data->object->brand . " ending in " . $event_json->data->object->last4, "disp" => 1),
    "customer.source.updated" => array("lib" => "Customer " . $event_json->data->object->customer . " updated a card " . $event_json->data->object->brand . " ending in " . $event_json->data->object->last4, "disp" => 0),
    "customer.source.deleted" => array("lib" => "Customer " . $event_json->data->object->customer . " deleted a card " . $event_json->data->object->brand . " ending in " . $event_json->data->object->last4, "disp" => 0),
    "customer.subscription.created" => array("lib" => "Customer created a subscription", "disp" => 0),
    "customer.subscription.updated" => array("lib" => "Customer updated a subscription", "disp" => 0),
    "customer.discount.created" => array("lib" => "Discount created for client", "disp" => 1),
    "invoice.created" => array("lib" => "An invoice has been created for " . money_format('%i', $event_json->data->object->amount_due / 100) . "£", "disp" => 1),
    "invoice.updated" => array("lib" => "An invoice has been updated", "disp" => 0),
    "invoice.payment_succeeded" => array("lib" => "Invoice payment succeeded", "disp" => 1),
    "invoice.payment_failed" => array("lib" => "Invoice payment failed", "disp" => 1),
    "invoiceitem.created" => array("lib" => "Invoice item created", "disp" => 0),
    "invoiceitem.updated" => array("lib" => "Invoice item updated", "disp" => 0),
);
//todo: make a priority and add levels of notification

if (!isset($events[$event_json->type])) {
    $desc = implode(" ", explode(".", $event_json->type));
    $disp = 1;
}
else {
    $desc = $events[$event_json->type]['lib'];
    $disp = $events[$event_json->type]['disp'];
}


if ($disp == 1) {

    $d = date("d-m-Y h:i:s");

    $marked = "@test";
    $src = "https://dashboard.stripe.com/test/events/" . $event_id;
    $data_string = '{"text": "' . $marked . '\nTest:\n' . $desc . '\nHook link: ' . $src . '\nDate: ' . $d . '"}';
    $ch = curl_init('https://hooks.slack.com/services/SLACK-HOOK-LINK');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
    );
    $result = curl_exec($ch);
}
