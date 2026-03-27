<?php
require_once 'Http/Controllers/product-inventory/ProductInventory.php';


// expenses routes
uri('add-product-inventory', 'App\ProductInventory', 'addProductInventory');

uri('product-inventory-store', 'App\ProductInventory', 'productInventoryStore', 'POST');

uri('search-seller', 'App\ProductInventory', 'searchSeller', 'POST');

uri('search-product-purchase', 'App\ProductInventory', 'searchProdut', 'POST');

uri('get-product-infos', 'App\ProductInventory', 'getProductInfos', 'POST');

// cart routes
uri('edit-product-cart/{id}', 'App\ProductInventory', 'editProductCart');

uri('edit-product-cart-store/{id}', 'App\ProductInventory', 'editProductCartStore', 'POST');

uri('delete-product-cart/{id}', 'App\ProductInventory', 'deleteProductCart');

uri('delete-invoice/{id}', 'App\ProductInventory', 'deleteInvoice');

uri('close-inventory-store', 'App\ProductInventory', 'closeBuyInvoiceStore', 'POST');




uri('products', 'App\ProductInventory', 'showProducts');
uri('edit-product/{id}', 'App\ProductInventory', 'editProduct');
uri('edit-product-store/{id}', 'App\ProductInventory', 'editProductStore', 'POST');
uri('product-details/{id}', 'App\ProductInventory', 'productDetails');
uri('change-status-product/{id}', 'App\ProductInventory', 'changeStatusProduct');


uri('get-invoice-items-ajax', 'App\ProductInventory', 'getInvoiceItemsAjax');

uri('item-update-warehouse/{id}', 'App\ProductInventory', 'itemUpdateWarehouse', 'POST');