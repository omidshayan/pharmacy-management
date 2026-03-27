<?php
require_once 'Http/Controllers/products-units/ProductUnit.php';

// Product category routes
uri('products-units', 'App\ProductUnit', 'productsUnits');
uri('product-unit-store', 'App\ProductUnit', 'productsUnitStore', 'POST');
uri('product-unit-details/{id}', 'App\ProductUnit', 'productUnitDetails');
uri('change-status-product-unit/{id}', 'App\ProductUnit', 'changeStatusProductUnit');
uri('edit-product-unit/{id}', 'App\ProductUnit', 'editProductUnit');
uri('edit-product-unit-store/{id}', 'App\ProductUnit', 'editProductUnitStore', 'POST');








