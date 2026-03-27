<?php
require_once 'Http/Controllers/expenses-categories/ExpensesCategory.php';

// expenses routes
uri('expenses_categories', 'App\ExpensesCategory', 'expensesCategories');
uri('expense-cat-store', 'App\ExpensesCategory', 'expenseCatStore', 'POST');
uri('expense-cat-details/{id}', 'App\ExpensesCategory', 'expenseCatDetails');
uri('edit-expense-cat/{id}', 'App\ExpensesCategory', 'editExpenseCat');
uri('edit-expense-cat-store/{id}', 'App\ExpensesCategory', 'editExpenseCatStore', 'POST');
uri('change-status-expense-cat/{id}', 'App\ExpensesCategory', 'changeStatusExpenseCat');
