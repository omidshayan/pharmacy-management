<?php
require_once 'Http/Controllers/products/Product.php';


// expenses routes
uri('add-product', 'App\Product', 'addproduct');
uri('product-store', 'App\Product', 'productStore', 'POST');
uri('products', 'App\Product', 'showProducts');
uri('edit-product/{id}', 'App\Product', 'editProduct');
uri('edit-product-store/{id}', 'App\Product', 'editProductStore', 'POST');
uri('product-details/{id}', 'App\Product', 'productDetails');
uri('change-status-product/{id}', 'App\Product', 'changeStatusProduct');



// categories products
uri('product-categories', 'App\Product', 'productCategories');
uri('product-cat-store', 'App\Product', 'productCatStore', 'POST');
uri('product-cat-details/{id}', 'App\Product', 'productCatDetails');
uri('change-status-product-cat/{id}', 'App\Product', 'changeStatusProductCat');
uri('edit-product-cat/{id}', 'App\Product', 'editProductCat');
uri('edit-product-cat-store/{id}', 'App\Product', 'editProductCatStore', 'POST');




// live search
uri('search-product', 'App\Product', 'searchProduct', 'POST');


// inventories
uri('inventories', 'App\Product', 'inventories');
