<?php

require_once './composer_load.php';

echo aamarpay_post_button([
    'cus_name'  => 'Shipu Ahamed', // Customer name
    'cus_email' => 'shipuahamed01@gmail.com', // Customer email
    'cus_phone' => '01616000000' // Customer Phone
], 2000, '<i class="fa fa-money">Payment</i>', 'btn btn-sm btn-success');

?>
