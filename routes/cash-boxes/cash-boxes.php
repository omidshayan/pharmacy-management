<?php
require_once 'Http/Controllers/cash-boxes/CashBoxes.php';

// funds routes
uri('cash-boxes', 'App\CashBoxes', 'cashBoxes');
uri('cash-box-store', 'App\CashBoxes', 'cashBoxStore', 'POST');
uri('edit-cash-box/{id}', 'App\CashBoxes', 'editCashBox');
uri('edit-cash-box-store/{id}', 'App\CashBoxes', 'editCashBoxStore', 'POST');
uri('cash-box-details/{id}', 'App\CashBoxes', 'cashBoxDetails');
uri('change-status-cash-box/{id}', 'App\CashBoxes', 'changeStatusCashBox');

uri('view-cash-boxes', 'App\CashBoxes', 'viewCashBoxes');











uri('transfer-to-main-fund', 'App\Fund', 'transferToMainFund', 'POST');

// center fund
uri('center-fund', 'App\Fund', 'centerFund');
uri('transfer-to-center-fund', 'App\Fund', 'transferToCenterFund', 'POST');




// uri('main-fund', 'App\Fund', 'mainFund');
