<?php
require_once 'Http/Controllers/reports/Financial.php';

// funds
uri('financial-summary', 'App\Financial', 'financialSummary');

// cardex product
uri('cardex-product/{id}', 'App\Financial', 'productCardex');


// uri('edit-invoice-store/{id}', 'App\Invoices', 'editInvoiceStore', 'POST');