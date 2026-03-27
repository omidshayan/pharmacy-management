<?php

namespace App;

require_once 'Http/Models/Calendar.php';
require_once 'Http/Models/Notification.php';

use Models\Calendar\Calendar;
use Models\Notification\Notification;

class Expenses extends App
{
    private $calendar;
    private $notification;

    public function __construct()
    {
        parent::__construct();
        $this->calendar = new Calendar();
        $this->notification = new Notification();
    }

    // add expense page
    public function addExpense()
    {
        $this->middleware(true, true, 'general', true);

        $branchId = $this->getBranchId();

        $expenses_categories = $this->db->select('SELECT * FROM expenses_categories WHERE branch_id = ? AND `status` = ?', [$branchId, 1])->fetchAll();

        $by_whom_employees = $this->db->select('SELECT * FROM employees WHERE branch_id = ? AND `state` = ?', [$branchId, 1])->fetchAll();

        $users = $this->db->select('SELECT id, `user_name` FROM users WHERE branch_id = ? AND `status` = ?', [$branchId, 1])->fetchAll();

        require_once(BASE_PATH . '/resources/views/app/expenses/add-expenses.php');
    }

    // store expense
    public function expenseStore($request)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        if ($request['category'] == '') {
            $this->flashMessage('error', _emptyInputs);
        }

        if ($request['price'] < $request['payment_expense']) {
            $this->flashMessage('error', 'مبلغ پرداختی از مبلغ مصرفی نباید بیشتر باشد!');
        }

        if ($request['price'] > $request['payment_expense']) {

            if ($request['user_id'] == '') {
                $this->flashMessage('error', 'چون مبلغ پرداختی کامل نیست، باید یک فروشنده انتخاب نمائید!');
            }

            $request['remainder_expense'] = intval($request['price'] - intval($request['payment_expense']));
        }


        $this->db->beginTransaction();

        try {

            // update fund
            $paid = $request['payment_expense'];
            $fund = $this->db->select(
                'SELECT id, total, income FROM funds WHERE branch_id = ?',
                [$request['branch_id']]
            )->fetch();

            $field = ($request['payment_from'] == 1) ? 'income' : 'total';

            if ($paid > $fund[$field]) {
                throw new \Exception('موجودی کافی نیست!');
            }

            $newValue = $fund[$field] - $paid;
            $this->db->update('funds', $fund['id'], [$field], [$newValue]);


            // year and month
            $yearMonth = $this->calendar->getYearMonth();
            $request['year'] = $yearMonth['year'];
            $request['month'] = $yearMonth['month'];

            // upload image
            $request['image_expense'] = $this->handleImageUpload($request['image_expense'], 'images/expenses');

            // insert expense
            $this->db->insert('expenses', array_keys($request), $request);
            $lastId = $this->db->lastInsertId();

            // send notifications
            $this->notification->sendNotif([
                'branch_id' => $request['branch_id'],
                'user_id' => $request['employee_name'],
                'ref_id' => $lastId,
                'type' => 8,
            ]);

            $this->db->commit();
            $this->flashMessage('success', _success);
        } catch (\Exception $e) {

            $this->db->rollBack();
            $this->flashMessage('error', $e->getMessage());
        }
    }

    // show expenses
    public function showExpenses()
    {
        $this->middleware(true, true, 'general');
        $expenses = $this->getTableData('expenses');

        require_once(BASE_PATH . '/resources/views/app/expenses/show-expenses.php');
        exit();
    }

    // edit expense page
    public function editexpense($id)
    {
        dd('به زودی اضافه می شود');
        $this->middleware(true, true, 'general', true);

        $expense = $this->db->select('SELECT * FROM expenses WHERE id = ?', [$id])->fetch();
        $expenses_categories = $this->db->select('SELECT * FROM expenses_categories WHERE `status` = 1')->fetchAll();
        $by_whom_employees = $this->db->select('SELECT * FROM employees WHERE `status` = 1')->fetchAll();
        if ($expense != null) {
            require_once(BASE_PATH . '/resources/views/app/expenses/edit-expense.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // edit expense store
    public function editExpenseStore($request, $id)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        if ($request['title_expenses'] == '' || !isset($request['category'])) {
            $this->flashMessage('error', _emptyInputs);
        }

        $expense = $this->db->select('SELECT * FROM expenses WHERE id = ?', [$id])->fetch();

        if ($expense != null) {
            $max_file_size = 1048576;
            if (is_uploaded_file($request['image_expense']['tmp_name'])) {
                if ($request['image_expense']['size'] > $max_file_size) {
                    $this->flashMessage('error', 'حجم عکس نباید بیشتر از 1 mb باشد');
                } else {
                    $this->removeImage('../application/public/images/expenses_images/' . $expense['image_expense']);
                    $request['image_expense'] = $this->saveImage($request['image_expense'], '../../application/public/images/expenses_images');
                }
            } else {
                unset($request['image_expense']);
            }
            $request['remainder_expense'] = intval($request['price'] - intval($request['payment_expense']));
            $this->db->update('expenses', $id, array_keys($request), $request);
            $this->flashMessage('success', _success);
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // expense detiles page
    public function expenseDetails($id)
    {
        $this->middleware(true, true, 'general');
        $expense = $this->db->select('SELECT * FROM expenses WHERE id = ?', [$id])->fetch();
        if ($expense != null) {
            require_once(BASE_PATH . '/resources/views/app/expenses/expense-details.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // change status expense
    public function changeStatusExpense($id)
    {
        $this->middleware(true, true, 'general');
        $expense = $this->db->select('SELECT * FROM expenses WHERE id = ?', [$id])->fetch();
        if ($expense != null) {
            if ($expense['status'] == 1) {
                $this->db->update('expenses', $expense['id'], ['status'], [2]);
                $this->send_json_response(true, _success, 2);
            } else {
                $this->db->update('expenses', $expense['id'], ['status'], [1]);
                $this->send_json_response(true, _success, 1);
            }
        } else {
            require_once BASE_PATH . '/404.php';
            exit();
        }
    }
}
