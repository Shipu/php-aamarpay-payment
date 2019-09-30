<?php
require __DIR__.'/../vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::create(__DIR__.'/../');

$dotenv->load();

$config = include(__DIR__.'/../config/aamarpay.php');


//AAMARPAY_SUCCESS_URL=http://localhost:8004/payment/success
//AAMARPAY_CANCEL_URL=http://localhost:8004/payment/cancel
