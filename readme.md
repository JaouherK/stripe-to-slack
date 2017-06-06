# Stripe notifications via Slack

PHP based background stripe notification system via slack. The result will be as follow:

![stripe to slack](/img/slack.png)

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.
You can sign up for a Stripe account at https://stripe.com.
You can sign up for a Slack account at https://slack.com/.

## Requirements

PHP 5.3.3 and later.

### Prerequisites

In Stripe 
 - create a webhook at https://dashboard.stripe.com/account/webhooks
 - get you API Keys at https://dashboard.stripe.com/account/apikeys
 
In Slack
 - get your webhook ready using https://api.slack.com/incoming-webhooks
 
 The acquired Keys or IDs should be inserted in the `config.php` file
```
define('STRIPE_API_KEY','your stripe key comes here');
define('SLACK_HOOK_LINK','ID generated from https://api.slack.com/incoming-webhooks');
```
 
### Installing

You can install the package using the Composer package manager. You can install it by running this command in your project root:
```
composer require JaouherK/stripe_to_slack
```
download the [latest release] of Stripe and copy it in the folder api (https://github.com/stripe/stripe-php/releases). The binding will be via the `init.php` file.


### Usage

You can use the function get_events() in the controller file to adjust the display of specific events:
example
```
"charge.succeeded" => array("lib" => "Customer successfully payed the amount", "disp" => 1),
```
Where `lib` is the text to be shown and `disp` is the possibility of sending this event to be shown in slack 0/1 value only

The lib can include some extra details from the sent Json file from Stripe
```
"customer.source.created" => array("lib" => "Customer " . $event_json->data->object->customer . " added a new " . $event_json->data->object->brand . " ending in " . $event_json->data->object->last4, "disp" => 1),
```

In order to tag people to be notified in Slack use the `config.php`
```
define('TAGS','your tagged people for notification instripe ie: @test');
```
If you are using more than one person to tag place them separated by a space (ie: `@test1 @test2`)

## Contributing

Please read [CONTRIBUTING.md]for details on our code of conduct, and the process for submitting pull requests to us.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* Thumbs up to anyone who's code was used
* Inspiration
* etc
