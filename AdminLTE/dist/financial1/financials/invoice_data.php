<?php
return [
    'invoice_number' => 'INV/2025/00013',
    'invoice_date' => '07/16/2025',
    'account_number' => '95960200000555 - BANK OF BARODA',
    'items' => [
        ['description' => 'Rental Income', 'quantity' => 1, 'unit_price' => 20000, 'tax' => '16%', 'amount' => 20000],
        ['description' => 'Garbage', 'quantity' => 1, 'unit_price' => 5000, 'tax' => '0%', 'amount' => 5000],
        ['description' => 'Water Charges', 'quantity' => 1, 'unit_price' => 1000, 'tax' => 'Exempt', 'amount' => 1000],
        ['description' => 'Electricity', 'quantity' => 1, 'unit_price' => 5000, 'tax' => '16%', 'amount' => 5000],
    ],
    'untaxed_total' => 31000,
    'vat_16' => 4000,
    'vat_0' => 0,
    'total' => 35000
];
