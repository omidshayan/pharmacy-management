<?php
require_once 'Http/Controllers/account-statement/AccountStatement.php';

// return from buy
uri('account-statement', 'App\AccountStatement', 'accountStatement');

uri('user-account-statement/{id}', 'App\AccountStatement', 'userAccountStatement');
uri('get-invoice/{id}', 'App\AccountStatement', 'getInvoice');

