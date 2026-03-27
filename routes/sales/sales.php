<?php
require_once 'Http/Controllers/sales/Sale.php';


// sales routes
uri('sales', 'App\Sale', 'showSales');

uri('add-sale', 'App\Sale', 'addSale');

uri('search-product-sale', 'App\Sale', 'searchProdutSale', 'POST');

uri('get-product-infos-sale', 'App\Sale', 'getProductInfosSale', 'POST');

uri('product-sale-store', 'App\Sale', 'productSaleStore', 'POST');


// sale cart routes

uri('delete-sale-product-cart/{id}', 'App\Sale', 'deleteSaleProductCart');

uri('delete-sale-invoice/{id}', 'App\Sale', 'deleteSaleInvoice');

uri('close-sale-inventory-store', 'App\Sale', 'closeSaleInvoiceStore', 'POST');

// uri('delete-all-sale-invoices', 'App\Sale', 'deleteAllSaleProductCart', 'POST');

uri('sale-invoice-details/{id}', 'App\Sale', 'saleInvoiceDetails');


uri('search-seller', 'App\ProductInventory', 'searchSeller', 'POST');

// added product list 
uri('get-sale-invoice-items-ajax', 'App\Sale', 'getSaleInvoiceItemsAjax');

uri('get-pro/{id}', 'App\Sale', 'getInvoiceProfit');