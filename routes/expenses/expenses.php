<?php
require_once 'Http/Controllers/expenses/Expenses.php';

// expenses routes
uri('add-expense', 'App\Expenses', 'addExpense');
uri('expense-store', 'App\Expenses', 'expenseStore', 'POST');
uri('expenses', 'App\Expenses', 'showExpenses');
uri('edit-expense/{id}', 'App\Expenses', 'editExpense');
uri('edit-expense-store/{id}', 'App\Expenses', 'editExpenseStore', 'POST');
uri('expense-details/{id}', 'App\Expenses', 'expenseDetails');
uri('change-status-expense/{id}', 'App\Expenses', 'changeStatusExpense');





