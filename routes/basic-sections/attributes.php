<?php
require_once 'Http/Controllers/basic-sections/attributes/Attribute.php';

// attributes routes
uri('attributes', 'App\Attribute', 'attributes');
uri('attribute-store', 'App\Attribute', 'attributeStore', 'POST');
uri('edit-attribute/{id}', 'App\Attribute', 'editAttribute');
uri('edit-attribute-store/{id}', 'App\Attribute', 'editAttributeStore', 'POST');
uri('attribute-details/{id}', 'App\Attribute', 'attributeDetails');
uri('change-status-attribute/{id}', 'App\Attribute', 'changeStatusAttribute');
