<?php
require_once 'Http/Controllers/invoices/Invoices.php';


// invoice routes
// uri('sale-invoice-details/{id}', 'App\Invoices', 'saleInvoiceDetails');

uri('edit-invoice-store/{id}', 'App\Invoices', 'editInvoiceStore', 'POST');


uri('update-invoice-item/{id}', 'App\Invoices', 'updateInvoiceItem', 'POST');

uri('update-sale-invoice-item/{id}', 'App\Invoices', 'updateSaleInvoiceItem', 'POST');

uri('invoice-details/{id}', 'App\Invoices', 'invoiceDetails');


// edit invoices
uri('edit-invoice/{id}', 'App\Invoices', 'editInvoice');

uri('get-edit-invoice-items-ajax/{id}', 'App\Invoices', 'getEditInvoiceItemsAjax');

// update item
uri('update-edit-invoice-item/{id}', 'App\Invoices', 'updateEditInvoiceItem', 'POST');

uri('edit-delete-product-cart/{id}', 'App\Invoices', 'editDeleteProductCart');

uri('cancel-invoice/{id}', 'App\Invoices', 'cancelInvoice');

uri('edit-invoice-item-store', 'App\Invoices', 'editInvoiceItemStore', 'POST');

uri('close-edit-invoice-store', 'App\Invoices', 'closeEditInvoiceStore', 'POST');
