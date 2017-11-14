<?php
use Shipu\Aamarpay\Aamarpay;

function aamarpay_post_button($input, $amount , $buttonText = 'Payment', $class = '') {
    return \Form::open([
            'method' => 'POST',
            'url'    => aamarpay_payment_url(),
            'style'  => 'display:inline'
        ]) . aamarpay_hidden_input($input, $amount) .
        \Form::button($buttonText,
            [ 'type' => 'submit', 'class' => $class ]) .
        \Form::close();
}

function aamarpay_hidden_input($input, $amount, $productDescription = 'T-shirt', $currency = 'BDT') {
    $payment = new Aamarpay(config('aamarpay'));
    return $payment->currency($currency)->product($productDescription)->customer($input)->amount($amount)->hiddenValue();
}

function aamarpay_payment_url() {
    $payment = new Aamarpay(config('aamarpay'));
    return $payment->paymentUrl();
}
