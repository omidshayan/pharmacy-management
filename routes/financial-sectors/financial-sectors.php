<?php
require_once 'Http/Controllers/financial-sectors/FinancialSector.php';


// sales routes
uri('deposit-money', 'App\FinancialSector', 'depositMoney');
uri('search-user', 'App\FinancialSector', 'searchUser', 'POST');
uri('deposit-money-store', 'App\FinancialSector', 'depositMoneyStore', 'POST');

uri('financial-sector', 'App\FinancialSector', 'financialSector');
uri('financial-sector-details/{id}', 'App\FinancialSector', 'financialSectorDetails');

// temp
uri('user-search', 'App\FinancialSector', 'userSearch');
uri('deposit-money/{id}', 'App\FinancialSector', 'depositMoneyT');