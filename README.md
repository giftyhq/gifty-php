# Gifty PHP SDK
[![Latest Stable Version](https://poser.pugx.org/gifty/gifty-php/v)](//packagist.org/packages/gifty/gifty-php)
![CI](https://github.com/giftyhq/gifty-php/workflows/CI/badge.svg?branch=master)
[![License](https://poser.pugx.org/gifty/gifty-php/license)](//packagist.org/packages/gifty/gifty-php)

PHP library for interacting with the Gifty API. This SDK is using the public [Gifty API](https://documenter.getpostman.com/view/3032027/RznEKynZ?version=latest) and enables you to:
- Accept gift cards in your webshop
- Redeem and issue gift cards in your POS-system

## Requirements
- PHP 7.2.0 and later
- A valid API Key, that can be [generated](https://dashboard.gifty.nl/settings/company/developers) in your Gifty dashboard

## Installation
The SDK is published on Packagist and can be installed using Composer.

`composer require gifty/gifty-php`

## Getting Started
Before starting it is recommended to read the documentation of the underlying [Gifty API](https://documenter.getpostman.com/view/3032027/RznEKynZ?version=latest) where all possible options to include are described.

Initializing the client and performing an API call is done as follows.

```php
$gifty = new \Gifty\Client\GiftyClient('eyJ0eXAi....');
$giftCard = $gifty->giftCards->get('ABCDABCDABCDABCD');
```

### Retrieve Locations

```php
$locations = $gifty->locations->all();
```

### Retrieve a Gift Card

```php
$giftCard = $gifty->giftCards->get('ABCDABCDABCDABCD');
```

### Retrieve all Transactions

```php
$giftCard = $gifty->giftCards->get('ABCDABCDABCDABCD');
$transactions = $giftCard->transactions->all();
```

### Retrieve a Transaction

```php
$giftCard = $gifty->giftCards->get('ABCDABCDABCDABCD');
$transaction = $giftCard->transactions->get('tr_BV94pGgqRvgobxvrLX28jEl0');
```

### Issue a Gift Card

```php
$giftCard = $gifty->giftCards->get('ABCDABCDABCDABCD');
$transaction = $giftCard->transactions->issue(
  [
    "amount" => 1250,
    "currency" => "EUR",
    "promotional" => false
  ]
);
```

### Redeem a Gift Card

```php
$giftCard = $gifty->giftCards->get('ABCDABCDABCDABCD');
$transaction = $giftCard->transactions->redeem(
  [
    "amount" => 1250,
    "currency" => "EUR",
    "capture" => false
  ]
);
```

### Capture a Transaction

```php
$giftCard = $gifty->giftCards->get('ABCDABCDABCDABCD');
$transaction = $giftCard->transactions->capture('tr_BV94pGgqRvgobxvrLX28jEl0');
```


### Release a Transaction

```php
$giftCard = $gifty->giftCards->get('ABCDABCDABCDABCD');
$transaction = $giftCard->transactions->release('tr_BV94pGgqRvgobxvrLX28jEl0');
```

## Development
Clone the Git repository so you have a local working copy.

`git clone https://github.com/giftyhq/gifty-php`

Install required (developing) dependencies using Composer.

`composer install`

Run and create PHPUnit tests for your modifications.

`composer test`

Make sure you follow the PSR12 coding standards.

`composer phpstan` & `composer phpcs`
