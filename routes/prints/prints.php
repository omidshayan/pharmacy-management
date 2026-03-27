<?php
require_once 'Http/Controllers/prints/Prints.php';

// exec cron job routes
uri('sale-print-item/{id}', 'App\Prints', 'salePrintItem');

uri('financial-print-item/{id}', 'App\Prints', 'financialPrintItem');

// general prints items
uri('item-print/{id}', 'App\Prints', 'itemPrint');


uri('print', 'App\Prints', 'print');


// sales
uri('sale-invoice-print/{id}', 'App\Prints', 'saleInvoicePrint');
uri('sale-single-print/{id}', 'App\Prints', 'saleSinglePrint');

// invoices print
uri('invoice-print/{id}', 'App\Prints', 'invoicePrint');
