<?php
require_once 'Http/Controllers/settings/Setting.php';

// settings routes


uri('manage-years', 'App\Setting', 'manageYears');
uri('change-status-years/{id}', 'App\Setting', 'changeStatusYears', 'POST');

// general settings
uri('general-settings', 'App\Setting', 'generalSettings');

uri('change-status-sale-invoice', 'App\Setting', 'changeStatusSaleInvoice', 'POST');
uri('change-status-buy-invoice', 'App\Setting', 'changeStatusBuyInvoice', 'POST');
uri('change-status-warehouse', 'App\Setting', 'changeStatusWarehouse', 'POST');
uri('change-status-expiration', 'App\Setting', 'changeStatusExpiration', 'POST');
uri('change-status-help-status', 'App\Setting', 'changeStatusHelpStatus', 'POST');


uri('factor-settings', 'App\Setting', 'factorSettings');
uri('factor-settings-store', 'App\Setting', 'factorSettingsStore', 'POST');



