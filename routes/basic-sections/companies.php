<?php
require_once 'Http/Controllers/basic-sections/companies/Company.php';

// companies routes
uri('companies', 'App\Company', 'companies');
uri('company-store', 'App\Company', 'companyStore', 'POST');
uri('edit-company/{id}', 'App\Company', 'editCompany');
uri('edit-company-store/{id}', 'App\Company', 'editCompanyStore', 'POST');
uri('company-details/{id}', 'App\Company', 'companyDetails');
uri('change-status-company/{id}', 'App\Company', 'changeStatusCompany');

