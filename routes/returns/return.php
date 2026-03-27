<?php
require_once 'Http/Controllers/returns/Returns.php';

// return from sale
uri('return-from-sale', 'App\Returns', 'returnFromSale');
uri('return-from-sale-store', 'App\Returns', 'returnSaleStore', 'POST');
uri('close-return-sale-store', 'App\Returns', 'closeReturnSaleStore', 'POST');
uri('returns-sales', 'App\Returns', 'returnSales');
uri('return-sale-invoice-details/{id}', 'App\Returns', 'returnSaleDetails');
uri('return-sale-store', 'App\Returns', 'returnSaleStore', 'POST');

// added product list 
uri('get-return-invoice-items-ajax', 'App\Returns', 'getReturnInvoiceItemsAjax');
// uri('get-pro/{id}', 'App\Sale', 'getInvoiceProfit');

// return from buy
uri('get-return-buy-invoice-items-ajax', 'App\Returns', 'getReturnBuyInvoiceItemsAjax');
uri('return-from-buy', 'App\Returns', 'returnFromBuy');
uri('return-buy-store', 'App\Returns', 'returnBuyStore', 'POST');
uri('close-return-buy-store', 'App\Returns', 'closeReturnBuyStore', 'POST');
uri('returns-buy', 'App\Returns', 'returnBuy');
uri('return-buy-invoice-details/{id}', 'App\Returns', 'returnBuyDetails');

// general
uri('return-delete-cart/{id}', 'App\Returns', 'returnDeleteCart');
uri('return-search-product', 'App\Returns', 'returnSearchProdut', 'POST');
uri('get-product-infos-return', 'App\Returns', 'getProductInfosReturn', 'POST');
uri('delete-return-invoice/{id}', 'App\Returns', 'deleteReturnInvoice');
uri('returns', 'App\Returns', 'returns');



// uri('search-return-from-sale', 'App\Returns', 'searchReturnFromSale', 'POST');
