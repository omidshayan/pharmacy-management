<?php
require_once 'Http/Controllers/warehouses/Warehouse.php';


// warehouses routes
uri('warehouses', 'App\Warehouse', 'warehouses');

uri('warehouse-store', 'App\Warehouse', 'warehouseStore', 'POST');
