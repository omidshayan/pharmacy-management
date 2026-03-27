<?php
// dashboard
require_once 'dashboard.php';

// send mail
require_once 'send-mail/send-mail.php';

// products
require_once 'products/products.php';

// Auth
require_once 'auth/auth.php';

// profile
require_once 'profile/profile.php';

// employees
require_once 'employees/employees.php';

// employees
require_once 'sections/sections.php';

// expenses categories
require_once 'expenses-categories/expenses-categories.php';

// expenses
require_once 'expenses/expenses.php';

// positions
require_once 'positions/positions.php';

// settings
require_once 'settings/settings.php';

// users
require_once 'users/users.php';

// products category
require_once 'products-units/products-units.php';

// product_inventory
require_once 'product-inventory/product-inventory.php';

// user agent
require_once 'user-agent.php';

// invoices 
require_once 'purchase-invoices/purchase-invoices.php';

// sales 
require_once 'sales/sales.php';
require_once 'sales/edit-sales.php';

// financial-sectors 
require_once 'financial-sectors/financial-sectors.php';

// prints 
require_once 'prints/prints.php';

// invoices
require_once 'invoices/invoices.php';

// return
require_once 'returns/return.php';

// salaries
require_once 'salaries/salaries.php';

// notifications
require_once 'notifications/notifications.php';

// account statement
require_once 'account-statement/account-statement.php';

// financial
require_once 'reports/financial.php';

// warehouses
require_once 'warehouses/warehouses.php';

// branches
require_once 'branches/branches.php';

// cash-boxes
require_once 'cash-boxes/cash-boxes.php';

// attributes
require_once 'basic-sections/attributes.php';

// payment-slip
// require_once 'payment-slip/payment-slip.php';

// cron job
require_once 'cron-job/cron_job.php';

// companies
require_once 'basic-sections/companies.php';



function uri($reservedUrl, $class, $method, $requestMethod = 'GET')
{
        //current url array
        $currentUrl = explode('?', currentUrl())[0];
        $currentUrl = str_replace(CURRENT_DOMAIN, '', $currentUrl);
        $currentUrl = trim($currentUrl, '/');
        $currentUrlArray = explode('/', $currentUrl);
        $currentUrlArray = array_filter($currentUrlArray);

        //reserved Url array
        $reservedUrl = trim($reservedUrl, '/');
        $reservedUrlArray = explode('/', $reservedUrl);
        $reservedUrlArray = array_filter($reservedUrlArray);

        if (sizeof($currentUrlArray) != sizeof($reservedUrlArray) || methodField() != $requestMethod) {
                return false;
        }

        $parameters = [];
        for ($key = 0; $key < sizeof($currentUrlArray); $key++) {
                if ($reservedUrlArray[$key][0] == "{" && $reservedUrlArray[$key][strlen($reservedUrlArray[$key]) - 1] == "}") {
                        array_push($parameters, $currentUrlArray[$key]);
                } elseif ($currentUrlArray[$key] !== $reservedUrlArray[$key]) {
                        return false;
                }
        }

        if (methodField() == 'POST') {
                $request = isset($_FILES) ? array_merge($_POST, $_FILES) : $_POST;
                $parameters = array_merge([$request], $parameters);
        }

        $object = new $class;
        call_user_func_array(array($object, $method), $parameters);
        exit();
}
