<?php
require_once 'Http/Controllers/purchase-invoices/PurchaseInvoices.php';

// expenses routes
uri('purchase-invoices', 'App\PurchaseInvoices', 'purchaseInvoices');

uri('purchase-invoice-details/{id}', 'App\PurchaseInvoices', 'purchaseInvoicesDetails');

uri('edit-product-cart-store/{id}', 'App\ProductInventory', 'editProductCartStore', 'POST');

uri('delete-product-cart/{id}', 'App\ProductInventory', 'deleteProductCart');

uri('delete-invoice/{id}', 'App\ProductInventory', 'deleteInvoice');

// edit invoice
uri('edit-buy-invoice/{id}', 'App\PurchaseInvoices', 'editBuyInvoice');

