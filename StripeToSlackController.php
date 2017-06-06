<?php

/**
 * PHP based background stripe notification system via slack.
 *
 * @category   WebHook
 * @package    StripeToSlack
 * @author     Jaouher Kharrat <kharratjaouher@gmail.com>
 * @copyright  2017-2017 JK
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    Release: 1.1
 * @link       https://github.com/JaouherK/stripe_to_slack
 * @since      Class available since Release 1.0.1
 */

class StripeToSlackController {
    /**
     * Initializes the Slack handler, loading the authentication
     * information from a config file.
     *
     * @return object array containing webhook content
     */
    function get_incoming_event()
    {
        \Stripe\Stripe::setApiKey(STRIPE_API_KEY);
        $input = @file_get_contents("php://input");
        return json_decode($input);
    }

    /**
     * Function used to adjust the display of specific events.
     * 'lib' is the text to be shown and 'disp' is the possibility of sending this event to be shown in slack 0/1 value only.
     * The list can be added and or modified as needed
     * @param $event_json
     * @return array Containing list of events.
     */
    function get_events($event_json)
    {
        return array(
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
    }

    /**
     * Return a specific info about an event depending on incoming type and identified display
     * @param $event array is a List of events identified in get_events($event_json)
     * @param $type
     * @return array
     */
    function get_event_info($event, $type)
    {
        if (!isset($event)) {
            $desc = implode(" ", explode(".", $type));
            $disp = 1;
        } else {
            $desc = $event['lib'];
            $disp = $event['disp'];
        }
        return array('desc' => $desc, 'disp' => $disp);
    }

    /**
     * Function to send notification to slack
     * @param $event_id
     * @param $desc
     */
    function slack_notification($event_id, $desc)
    {

        $d = date("d-m-Y h:i:s");
        $marked = TAGS;
        $src = "https://dashboard.stripe.com/test/events/" . $event_id;
        $data_string = '{"text": "' . $marked . '\nTest:\n' . $desc . '\nHook link: ' . $src . '\nDate: ' . $d . '"}';
        $ch = curl_init('https://hooks.slack.com/services/' . SLACK_HOOK_LINK);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );
        $result = curl_exec($ch);
    }
}