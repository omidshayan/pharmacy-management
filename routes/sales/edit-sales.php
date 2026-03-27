<?php
require_once 'Http/Controllers/sales/EditInvoiceSale.php';

// edit sales routes


uri('delete-sale-product-cart/{id}', 'App\Sale', 'deleteSaleProductCart');

uri('delete-sale-invoice/{id}', 'App\Sale', 'deleteSaleInvoice');

uri('close-sale-inventory-store', 'App\Sale', 'closeSaleInvoiceStore', 'POST');


// card
uri('edit-sale-product-cart/{id}', 'App\Sale', 'editSaleProductCart');

uri('edit-sale-product-cart-store/{id}', 'App\Sale', 'editSaleProductCartStore', 'POST');
