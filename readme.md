# Stripe notifications via Slack

PHP based background stripe notification system via slack

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
 
### Installing

You can install the package using the Composer package manager. You can install it by running this command in your project root:
```
composer require JaouherK/invoice-generator
```
download the [latest release] of Stripe and copy it in the folder api (https://github.com/stripe/stripe-php/releases). The binding will be via the `init.php` file.

## Contributing

Please read [CONTRIBUTING.md](https://gist.github.com/PurpleBooth/b24679402957c63ec426) for details on our code of conduct, and the process for submitting pull requests to us.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* Thumbs up to anyone who's code was used
* Inspiration
* etc
