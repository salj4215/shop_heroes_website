# HFC CIS Mock Credit Card Authorization API

This mock credit card authorization API was created by Micah Webner for use by CIS class projects at Henry Ford College.

**IMPORTANT:** Do NOT post real credit card numbers when testing applications. Make up any 13- to 16-digit number.

This API sends simulated credit card authorization requests to a fake authorization server. The server will return failed transactions (either "Invalid card" or "Card declined" messages approximately 1 in 10 times.)

The API and the authorization service are in early alpha testing. This document will be updated as the system is developed.

## Installation

1. Clone the https://gitlab.hfcc.edu/cis/ccauthapi repository into a new folder in your project.
2. In the directory where the API is stored, copy config-default.php to config.php and fill in the values provided by your instructor.

## Usage

You can include the Credit Card Authorization API in your PHP project by using the following code:

```
require_once(dirname(__FILE__) . '/ccauthapi/CCAuthApi.php');

$values = [
  "merchant_id" => $your_merchant_id,
  "transaction_key" => $your_transaction_key,
  "transaction_type" => "sale",
  "amount" => "54.97",
  "cardholder_firstname" => "John",
  "cardholder_lastname" => "Smith",
  "card_number" => "4111222233334444",
  "card_expiration" => "1225",
  "cvv" => "0369",
];

$authorization = CCAuthApi::create($values)->response();
```

## Request Properties

The minimum required request properties are shown above. Here is the complete list of accepted properties:

* merchant_id - assigned by system
* transaction_key - assigned by system
* transaction_type - valid values "sale" (for now)
* amount - transaction amount
* cardholder_firstname
* cardholder_lastname
* card_number
* card_expiration
* cvv
* invoice_number - optional
* description - optional
* customer_id - optional
* customer_email - optional
* customer_zip - optional
* customer_phone - optional

## Response Properties

The system should return the following response properties:

* response_code
  - 1: Approved
  - 2: Declined
  - 3: Error
  - 4: Unknown Merchant ID
  - 5: Invalid Merchant Transaction Key
* message - response message
* auth_code - authorization code
* transaction_id - system transaction id
* account_number - last 4 digits of card
* amount - transaction amount
