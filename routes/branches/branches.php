<?php
require_once 'Http/Controllers/branches/Branches.php';


// warehouses routes
uri('branches', 'App\Branches', 'branches');

uri('branch-store', 'App\Branches', 'branchStore', 'POST');
