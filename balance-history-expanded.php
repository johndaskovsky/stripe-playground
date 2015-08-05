<?php

require_once(dirname(__FILE__) . '/init.php');

require_once(dirname(__FILE__) . '/config.php');

$arr = array();

//headers
array_push($arr,
  array(
    'balance_id',
    'charge_id',
    'type',
    'amount',
    'net',
    'fee',
    'created',
    'available_on',
    'status',
    'description',
    'product_id',
    'product_type',
    'order_id',
    'customer_name',
    'customer_email'
  )
);

$out = fopen('balance_history_expanded.csv', 'w');

$increment = 100;
$continue = true;
$starting_after = NULL;

while($continue) {
  $transactionsJSON = \Stripe\BalanceTransaction::all(array("limit" => $increment, "starting_after" => $starting_after));

  $transactions = $transactionsJSON->__toArray(true);

  $continue = $transactions['has_more'];

  $starting_after = end($transactions['data'])['id'];

  foreach ($transactions['data'] as $transaction) {
    array_push($arr,
      array(
        $transaction['id'],
        $transaction['source'],
        $transaction['type'],
        $transaction['amount']/100,
        $transaction['net']/100,
        $transaction['fee']/100,
        gmdate("Y-m-d", $transaction['created']),
        gmdate("Y-m-d", $transaction['available_on']),
        $transaction['status'],
        $transaction['description']
        //$charge['metadata']['product_id'],
        //$charge['metadata']['product_type'],
        //$charge['metadata']['order_id'],
        //$charge['metadata']['customer_name'],
        //$charge['metadata']['customer_email']
      )
    );
  }

}

foreach ($arr as $rows) {
    fputcsv($out, $rows);
}
fclose($out);






