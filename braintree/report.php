<?php
date_default_timezone_set('America/Chicago');

require_once 'lib/Braintree.php';

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('5xwqxqd7pkqwxfjs');
Braintree_Configuration::publicKey('8pfkt34wzyvrnnwy');
Braintree_Configuration::privateKey('8b63a0ae59085a11912527848633b848');

$output = fopen('transaction_report.csv', 'w');

fputcsv($output, ['id', 'type', 'amount', 'status', 'created_at', 'service_fee_amount', 'merchant_account_id']);

$now = new DateTime();
$yesterday = $now->modify('-365 day');

$transactions = Braintree_Transaction::search([
  Braintree_TransactionSearch::settledAt()->greaterThanOrEqualTo($yesterday)
]);

foreach($transactions as $transaction) {
    $id = $transaction->id;
    $type = $transaction->type;
    $amount = $transaction->amount;
    $status = $transaction->status;
    $createdAt = $transaction->createdAt->format('d/m/Y H:i:s');
    $serviceFeeAmount = $transaction->serviceFeeAmount;
    $merchantAccountId = $transaction->merchantAccountId;

    $csvrow = [$id, $type, $amount, $status, $createdAt, $serviceFeeAmount, $merchantAccountId];
    fputcsv($output, $csvrow);
}

echo "Download the CSV file <a href='transaction_report.csv'>Download</a>"

?>