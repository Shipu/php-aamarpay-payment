# PHP Aamarpay Payment Gateway

php-aamarpay-payment is a PHP client for Aamarpay Payment Gateway API. This package is also support Laravel and Lumen.

## Installation

Go to terminal and run this command

```shell
composer require shipu/php-aamarpay-payment
```

Wait for few minutes. Composer will automatically install this package for your project.

### For Laravel

Below **Laravel 5.5** open `config/app` and add this line in `providers` section

```php
Shipu\Aamarpay\AamarpayServiceProvider::class,
```

For Facade support you have add this line in `aliases` section.

```php
'Aamarpay'   =>  Shipu\Aamarpay\Facades\Aamarpay::class,
```

Then run this command

```shell
php artisan vendor:publish --provider="Shipu\Aamarpay\AamarpayServiceProvider"
```

## Configuration

This package is required three configurations.

1. store_id = your store id in Aamarpay Payment Gateway.
2. signature_key = your signature key in Aamarpay Payment Gateway
3. sandbox = `true` for sandbox and `false` for live
4. redirect_url = your application redirect url after `success` and `fail`.

php-aamarpay-payment is take an array as config file. Lets services

```php
use Shipu\Aamarpay\Aamarpay;

$config = [
    'store_id' => 'Your store id',
    'signature_key' => 'Your signature key',
    'sandbox' => true,
    'redirect_url' => [
        'success' => [
            'route' => 'payment.success'
        ],
        'cancel' => [
            'route' => 'payment.cancel' 
        ]
    ]
];

$payment = new Aamarpay($config);
```
### For Laravel

This package is also support Laravel. For laravel you have to configure it as laravel style.

Go to `app\aamarpay.php` and configure it with your credentials.

```php
return [
    'store_id' => 'Your store id',
    'signature_key' => 'Your signature key',
    'sandbox' => true,
    'redirect_url' => [
        'success' => [
            'route' => 'payment.success'
        ],
        'cancel' => [
            'route' => 'payment.cancel' 
        ]
    ]
];
```

## Usages

- Mandatory input field name
    - tran_id // auto generate by this package
    - cus_name
    - cus_email
    - cus_phone
    - desc
    - currency // auto generate by this package
    - amount

#### Getting Payment Post Url

In PHP:
```php
use \Shipu\Aamarpay\Aamarpay;

...

$payment = new Aamarpay($config);
return $payment->paymentUrl();
```
In Laravel:
```php
use \Shipu\Aamarpay\Aamarpay;

...

$payment = new Aamarpay(config('aamarpay'));
return $payment->paymentUrl();
```

#### Getting Hidden Input Field
```php
use \Shipu\Aamarpay\Aamarpay;

...

$payment = new Aamarpay(config('aamarpay'));
return $payment->customer([
    'cus_name'  => 'Shipu Ahamed', // Customer name
    'cus_email' => 'shipuahamed01@gmail.com', // Customer email
    'cus_phone' => '01616022669' // Customer Phone
])->transactionId('21005455540')->amount(3500)->hiddenValue();
```
Where Transaction id is random value. you can generate by yourself or follow bellow steps:
```php
use \Shipu\Aamarpay\Aamarpay;

...

$payment = new Aamarpay(config('aamarpay'));
return $payment->customer([
    'cus_name'  => 'Shipu Ahamed', // Customer name
    'cus_phone' => '01616022669' // Customer Phone
    'cus_email' => 'shipuahamed01@gmail.com', // Customer email
])->transactionId()->amount(3500)->hiddenValue();

or 

return $payment->customer([
    'cus_name'  => 'Shipu Ahamed', // Customer name
    'cus_phone' => '01616022669' // Customer Phone
    'cus_email' => 'shipuahamed01@gmail.com', // Customer email
])->amount(3500)->hiddenValue();
```
Default currency is `BDT` . For change currency:
```
return $payment->customer([
    'cus_name'  => 'Shipu Ahamed', // Customer name
    'cus_phone' => '01616022669' // Customer Phone
    'cus_email' => 'shipuahamed01@gmail.com', // Customer email
])->currency()->amount(3500)->hiddenValue();
```

#### Generate Transaction Id
```php
use \Shipu\Aamarpay\Aamarpay;

...

$payment = new Aamarpay(config('aamarpay'));
return $payment->generateTransaction();
```

#### Checking Valid Response
```php
use \Shipu\Aamarpay\Aamarpay;

...

$payment = new Aamarpay(config('aamarpay'));
return $payment->valid($request);
```
Checking valid response with amount:
```php
use \Shipu\Aamarpay\Aamarpay;

...

$payment = new Aamarpay(config('aamarpay'));
return $payment->valid($request, '3500'); 
```
Where `$request` will appear after post response.

## In Blade

#### Getting Payment Post Url
```php
{{ aamarpay_payment_url() }}
```

#### Getting Hidden Input Field
```php
{!!
    aamarpay_hidden_input([
        'tran_id'   => '21005455540', // random number. if you don't set this it will be auto generate.
        'cus_name'  => 'Shipu Ahamed', // Customer name
        'cus_email' => 'shipuahamed01@gmail.com', // Customer email
        'cus_phone' => '01616022669' // Customer Phone
    ], 3500) 
!!}
or
{!!
    aamarpay_hidden_input([
        'tran_id'   => '21005455540', // random number. if you don't set this it will be auto generate.
        'cus_name'  => 'Shipu Ahamed', // Customer name
        'cus_email' => 'shipuahamed01@gmail.com', // Customer email
        'cus_phone' => '01616022669' // Customer Phone
    ], 3500, 'T-shirt', 'BDT') 
!!}
```

#### Complete Post Button View 
```php
{!! 
aamarpay_post_button([
    'cus_name'  => 'Shipu Ahamed', // Customer name
    'cus_email' => 'shipuahamed01@gmail.com', // Customer email
    'cus_phone' => '01616000000' // Customer Phone
], 2000, '<i class="fa fa-money">Payment</i>', 'btn btn-sm btn-success') 
!!}
```
## Example 

##### Route
```php
Route::post('payment/success', 'YourMakePaymentsController@paymentSuccess')->name('payment.success');
Route::post('payment/failed', 'YourMakePaymentsController@paymentFailed')->name('payment.failed');
Route::post('payment/cancel', 'YourMakePaymentsController@paymentCancel')->name('payment.cancel');
```

or 

```php
Route::post('payment/success', 'YourMakePaymentsController@paymentSuccessOrFailed')->name('payment.success');
Route::post('payment/failed', 'YourMakePaymentsController@paymentSuccessOrFailed')->name('payment.failed');
Route::post('payment/cancel', 'YourMakePaymentsController@paymentSuccessOrFailed')->name('payment.cancel');

```
##### Controller Method
```php
use Shipu\Aamarpay\Facades\Aamarpay;

...

public function paymentSuccessOrFailed(Request $request)
{
    if($request->get('pay_status') == 'Failed') {
        return redirect()->back();
    }
    
    $amount = 3500;
    $valid  = Aamarpay::valid($request, $amount);
    
    if($valid) {
        // Successfully Paid.
    } else {
       // Something went wrong. 
    }
    
    return redirect()->back();
}
```

## To Disable CSRF token
Open `app/Http/Middleware/VerifyCsrfToken.php` and adding :
```php
protected $except = [
    ...
    'payment/*',
    ...
];
```

## Credits

- [Shipu Ahamed](https://github.com/shipu)
- [All Contributors](../../contributors)

## Support on Beerpay
Hey dude! Help me out for a couple of :beers:!

[![Beerpay](https://beerpay.io/Shipu/php-aamarpay-payment-/badge.svg?style=beer-square)](https://beerpay.io/Shipu/php-aamarpay-payment-)  [![Beerpay](https://beerpay.io/Shipu/php-aamarpay-payment-/make-wish.svg?style=flat-square)](https://beerpay.io/Shipu/php-aamarpay-payment-?focus=wish)
