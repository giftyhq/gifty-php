# Gifty PHP SDK
[![Latest Stable Version](https://poser.pugx.org/gifty/gifty-php/v)](//packagist.org/packages/gifty/gifty-php)
![CI](https://github.com/giftyhq/gifty-php/workflows/CI/badge.svg?branch=master)
[![License](https://poser.pugx.org/gifty/gifty-php/license)](//packagist.org/packages/gifty/gifty-php)

PHP library for interacting with the Gifty API. This SDK is using the public [Gifty API](https://docs.gifty.nl/api) and enables you to:
- Accept gift cards in your webshop
- Redeem and issue gift cards in your POS-system
- Retrieve gift card packages
- Retrieve store locations

## Requirements
- PHP 7.4.0 and later
- A valid API Key, that can be [generated](https://dashboard.gifty.nl/settings/company/developers) in your Gifty dashboard

## Installation
The SDK is published on Packagist and can be installed using Composer.

`composer require gifty/gifty-php`

## Getting Started
Before starting, it is recommended to read the documentation of the underlying [Gifty API](https://docs.gifty.nl/api) where all possible options to include are described.

Initializing the client and performing an API call is done as follows.

```php
$gifty = new \Gifty\Client\GiftyClient('eyJ0eXAi....');
$giftCard = $gifty->giftCards->get('ABCDABCDABCDABCD');
```

You can also pass additional headers to the client.

```php
$gifty = new \Gifty\Client\GiftyClient('eyJ0eXAi....', ['api_headers' => [
  'Accept-Language' => 'en',
  'X-Gifty-Location' => 'lc_123456789'
]]);
$giftCard = $gifty->giftCards->get('ABCDABCDABCDABCD');
```

### Retrieve Locations

```php
$locations = $gifty->locations->all();
```

### Retrieve Packages

```php
$packages = $gifty->packages->all();
```

### Retrieve a Package

```php
$package = $gifty->packages->get('gp_ABCDABCD');
```

### Retrieve a Gift Card

```php
$giftCard = $gifty->giftCards->get('ABCDABCDABCDABCD');
```

### Issue a Gift Card

```php
$transaction = $gifty->giftCards->issue(
  'ABCDABCDABCDABCD',
  [
    "amount" => 1250,
    "currency" => "EUR",
    "promotional" => false
  ]
);
```

### Redeem a Gift Card

```php
$transaction = $gifty->giftCards->redeem(
  'ABCDABCDABCDABCD',
  [
    "amount" => 1250,
    "currency" => "EUR",
    "capture" => false
  ]
);
```

### Extend a Gift Card

```php
$transaction = $gifty->giftCards->extend(
  'ABCDABCDABCDABCD',
  [
    "expires_at" => "2027-09-15T12:42:42+00:00"
  ]
);
```

### Retrieve all Transactions

```php
$transactions = $gifty->transactions->all(['limit' => 5]);
```

### Retrieve all Transactions filtered by gift card ID

```php
$transactions = $gifty->transactions->all(['giftcard' => 'gc_123456789']);
```

### Retrieve a Transaction

```php
$transaction = $gifty->transactions->get('tr_BV94pGgqRvgobxvrLX28jEl0');
```

### Capture a Transaction

```php
$transaction = $gifty->transactions->capture('tr_BV94pGgqRvgobxvrLX28jEl0');
```


### Release a Transaction

```php
$transaction = $gifty->transactions->release('tr_BV94pGgqRvgobxvrLX28jEl0');
```

## Development
Clone the Git repository, so you have a local working copy.

`git clone https://github.com/giftyhq/gifty-php`

Install required (developing) dependencies using Composer.

`composer install`

Run and create PHPUnit tests for your modifications.

`composer test`

Make sure you follow the PSR12 coding standards.

`composer phpstan` & `composer phpcs`
