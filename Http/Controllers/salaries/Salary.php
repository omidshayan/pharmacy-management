<?php

namespace App;

require_once 'Http/Models/Calendar.php';
require_once 'Http/Models/Notification.php';
require_once 'Http/Models/Reports.php';

use Models\Calendar\Calendar;
use Models\Notification\Notification;
use Models\Reports\Reports;

class Salary extends App
{
    private $calendar;
    private $notification;
    private $reports;

    public function __construct()
    {
        parent::__construct();
        $this->calendar = new Calendar();
        $this->notification = new Notification();
        $this->reports = new Reports();
    }

    // add salaries page
    public function addSalary()
    {
        $this->middleware(true, true, 'general', true);
        $employees = $this->db->select('SELECT * FROM employees WHERE `state` = ? ', [1])->fetchAll();
        require_once(BASE_PATH . '/resources/views/app/salaries/add-salary.php');
    }

    // store salary
    public function salaryStore($request)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        // check empty form
        if ($request['search_input'] == '' || $request['amount'] == '' || $request['date'] == '') {
            $this->flashMessage('error', _emptyInputs);
        }

        $existingEmployee = $this->db->select('SELECT id, salary_price FROM employees WHERE `id` = ?', [$request['selected_item_id']])->fetch();
        if (!$existingEmployee) {
            require_once(BASE_PATH . '/404.php');
            exit();
        }

        $request['employee_id'] = $request['selected_item_id'];

        // get month and year
        $yearMonth = $this->calendar->getYearMonth();
        $request['year'] = $yearMonth['year'];
        $request['month'] = $yearMonth['month'];
        $request['base_salary'] = $existingEmployee['salary_price'];
        $emp_name = $request['search_input'];

        unset($request['selected_item_id']);
        unset($request['search_input']);
        
        // update fund
        $updateFund = [
            'branch_id'   => $request['branch_id'],
            'paid_amount' => $request['amount'],
            'type'        => 2,
            'source'      => isset($request['source']) ? (int)$request['source'] : 1,
        ];
        $this->reports->updateFund($updateFund);
        unset($request['source']);


        $request = $this->validateInputs($request);

        $this->db->insert('salary_transactions', array_keys($request), $request);
        $lastId = $this->db->lastInsertId();

        // send notificatons
        $this->notification->sendNotif([
            'branch_id' => $request['branch_id'],
            'user_id' => $request['employee_id'],
            'employee_name' => $emp_name,
            'ref_id' => $lastId,
            'type' => 7,
            'notif_type' => 3,
        ]);


        $this->flashMessage('success', _success);
    }

    // show salaries
    public function showSalaries()
    {
        $this->middleware(true, true, 'general');
        $salaries = $this->db->select('SELECT s.*, e.employee_name FROM salary_transactions s INNER JOIN employees e ON s.employee_id = e.id')->fetchAll();

        require_once(BASE_PATH . '/resources/views/app/salaries/salaries.php');
        exit();
    }

    // salary Details page
    public function salaryDetails($id)
    {
        $this->middleware(true, true, 'general');
        $branchId = $this->getBranchId();

        $salary = $this->db->select('SELECT s.*, e.employee_name FROM salary_transactions AS s JOIN employees AS e ON e.id = s.employee_id WHERE s.id = ? AND s.branch_id = ?', [$id, $branchId])->fetch();

        if ($salary != null) {
            require_once(BASE_PATH . '/resources/views/app/salaries/salary-details.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // edit salary page
    public function editSalary($id)
    {
        $this->middleware(true, true, 'general', true);
        $branchId = $this->getBranchId();

        $salary = $this->db->select('SELECT s.*, e.employee_name FROM salary_transactions AS s JOIN employees AS e ON e.id = s.employee_id WHERE s.id = ? AND s.branch_id = ?', [$id, $branchId])->fetch();

        if ($salary != null) {
            require_once(BASE_PATH . '/resources/views/app/salaries/edit-salary.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // edit salary store
    public function editSalaryStore($request, $id)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        if (isset($request['amount']) && trim($request['amount']) !== '') {
            $request['amount'] = $this->convertPersionNumber($request['amount']);
            $request['amount'] = preg_replace('/[^0-9.]/', '', $request['amount']);
        } else {
            $request['amount'] = 0;
        }

        if (!isset($request['amount']) || !is_numeric($request['amount']) || floatval($request['amount']) <= 0) {
            $this->flashMessage('error', _emptyInputs);
        }

        $salary = $this->db->select('SELECT * FROM salary_transactions WHERE id = ?', [$id])->fetch();

        if ($salary) {
            $this->db->update('salary_transactions', $id, array_keys($request), $request);
            $this->flashMessageTo('success', _success, url('salaries'));
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // change status employee
    public function changeStatusSalary($id)
    {
        $this->middleware(true, true, 'general');

        $salary = $this->db->select('SELECT * FROM salary_transactions WHERE id = ? ', [$id])->fetch();

        if (!$salary) {
            require_once BASE_PATH . '/404.php';
            exit;
        }

        $newStatus = $salary['status'] == 1 ? 2 : 1;

        $this->db->update('salary_transactions', $salary['id'], ['status'], [$newStatus]);
        $this->send_json_response(true, _success, $newStatus);
    }
}
