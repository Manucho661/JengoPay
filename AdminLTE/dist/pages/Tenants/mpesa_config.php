<?php
return [
    'ConsumerKey' => 'SjTrAk1B7iHo8yH1DZKsyzG9HKDLRlvwKGR3tBJniWEtkkNc', // From Safaricom Developer Portal
    'ConsumerSecret' => 'aibliGXFpGjaZblaZTT7J5608uHSGFGZz4vAiDMxwv3rTyFKAynI5f9lMxAJtyq9', // From Safaricom Developer Portal
    'BusinessShortCode' => '600996', // e.g., 174379 for sandbox
    'PassKey' => 'YOUR_PASSKEY', // From Safaricom Developer Portal
    'CallbackURL' => 'https://yourdomain.com/mpesa_callback.php', // Your callback URL
    'TransactionType' => 'CustomerPayBillOnline',
    'AccountReference' => 'RENTPAYMENT',
    'TransactionDesc' => 'Rent Payment'
];
?>