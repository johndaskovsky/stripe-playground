<?php

require_once(dirname(__FILE__) . '/init.php');

require_once(dirname(__FILE__) . '/config.php');

$increment = 100;
$count = 0;
$continue = true;
$starting_after = NULL;

while($continue) {
  $customersJSON = \Stripe\Customer::all(array("limit" => $increment, "starting_after" => $starting_after));

  $customers = $customersJSON->__toArray(true);

  $continue = $customers['has_more'];

  $starting_after = end($customers['data'])['id'];

  foreach ($customers['data'] as $customer) {
    if ( count($customer['subscriptions']['data']) == 0 ) {
      $cu = \Stripe\Customer::retrieve( $customer['id'] );
      $cu->delete();
    }
  }

}



