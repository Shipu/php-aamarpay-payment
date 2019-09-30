<?php

use Collective\Html\Eloquent\FormAccessible;
use Collective\Html\FormBuilder;
use Collective\Html\FormFacade;
use Shipu\Aamarpay\Aamarpay;

if (! function_exists('aamarpay_post_button')) {
    function aamarpay_post_button( $input, $amount, $buttonText = 'Payment', $class = '' )
    {
        return '<form action="'.aamarpay_payment_url().'" method="POST" style="display:inline">' .
                    aamarpay_hidden_input($input, $amount) .
                    '<button type="submit" class="'.$class.'">'.$buttonText.'</button>' .
               '</form>';
    }
}

if (! function_exists('aamarpay_hidden_input')) {
    function aamarpay_hidden_input( $input, $amount, $productDescription = 'T-shirt', $currency = 'BDT' )
    {
        $payment = new Aamarpay(config('aamarpay'));

        return $payment->currency($currency)->product($productDescription)->customer($input)->amount($amount)->hiddenValue();
    }
}

if (! function_exists('aamarpay_payment_url')) {
    function aamarpay_payment_url()
    {
        $payment = new Aamarpay(config('aamarpay'));

        return $payment->paymentUrl();
    }
}

if (! function_exists('config')) {
    function config($fileName)
    {
        require __DIR__.'/../vendor/autoload.php';

        $dotenv = \Dotenv\Dotenv::create(__DIR__.'/../');

        $dotenv->load();

        $config = include(__DIR__.'/../config/'.$fileName.'.php');

        return $config;
    }
}

if (! function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function env($key, $default = null)
    {
        $value = getenv($key);
        if ($value === false) {
            return collect_value($default);
        }
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return;
        }
        if (($valueLength = strlen($value)) > 1 && $value[0] === '"' && $value[$valueLength - 1] === '"') {
            return substr($value, 1, -1);
        }
        return $value;
    }
}


