<?php
require_once 'Http/Controllers/positions/Position.php';

// add product routes
uri('positions', 'App\Position', 'positions');
uri('position-store', 'App\Position', 'store', 'POST');


uri('edit-position/{id}', 'App\Position', 'editPosition');
uri('edit-position-store/{id}', 'App\Position', 'editPositionStore', 'POST');
uri('position-details/{id}', 'App\Position', 'positionDetails');

uri('change-status-position/{id}', 'App\Position', 'changeStatusPosition');





