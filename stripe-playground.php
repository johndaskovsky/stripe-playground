<?php

require_once(dirname(__FILE__) . '/init.php');

require_once(dirname(__FILE__) . '/config.php');

$increment = 5;
$count = 0;
$continue = true;
$starting_after = NULL;

while($continue) {
  $customersJSON = \Stripe\Customer::all(array("limit" => $increment, "starting_after" => $starting_after));

  $customers = $customersJSON->__toArray(true);

  $continue = $customers['has_more'];

  $starting_after = end($customers['data'])['id'];

  foreach ($customers['data'] as $customer) {
    foreach ($customer['subscriptions']['data'] as $subscription) {
        if( $subscription['discount']['coupon']['id'] == 'MKVVVFSC2QNJKX7XZNGQDIEAGUDZCZGORFJ') {
          echo 'Customer: ' . $subscription['customer'] . '<br>';
          echo 'Subscription: ' . $subscription['id'] . '<br>';
          echo 'Discount: ' . $subscription['discount']['coupon']['id'] . '<br><br>';
          $cu = \Stripe\Customer::retrieve( $subscription['customer'] );
          $cu->subscriptions->retrieve( $subscription['id'] )->deleteDiscount();  
        }
    }
  }
}


