<?php
require_once 'Http/Controllers/employees/Employee.php';

// add product routes
uri('add-employee', 'App\Employee', 'addEmployee');
uri('employees', 'App\Employee', 'showEmployees');
uri('employee-store', 'App\Employee', 'employeeStore', 'POST');
uri('employee-details/{id}', 'App\Employee', 'employeeDetails');
uri('edit-employee/{id}', 'App\Employee', 'editEmployee');
uri('edit-employee/store/{id}', 'App\Employee', 'editEmployeeStore', 'POST');
uri('change-status-employee/{id}', 'App\Employee', 'changeStatusEmployee');

uri('search-employee', 'App\Employee', 'searchEmployee', 'POST');