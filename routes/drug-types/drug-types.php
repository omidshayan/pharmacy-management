<?php
require_once 'Http/Controllers/drug-types/DrugType.php';

// Drug Types routes
uri('drug-types', 'App\DrugType', 'drugTypes');
uri('drug-type-store', 'App\DrugType', 'drugTypeStore', 'POST');
uri('drug-type-details/{id}', 'App\DrugType', 'drugTypesDetails');
uri('change-status-drug-type/{id}', 'App\DrugType', 'changeStatusDrugTypes');
uri('edit-drug-type/{id}', 'App\DrugType', 'editDrugTypes');
uri('edit-drug-type-store/{id}', 'App\DrugType', 'editDrugTypesStore', 'POST');
