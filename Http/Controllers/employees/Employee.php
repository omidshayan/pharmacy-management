<?php

namespace App;

require_once 'Http/Models/Calendar.php';

use Models\Calendar\Calendar;

class Employee extends App
{
    private $calendar;
    public function __construct()
    {
        parent::__construct();
        $this->calendar = new Calendar();
    }


    // add employee page
    public function addEmployee()
    {
        $this->middleware(true, true, 'general', true);
        $positions = $this->db->select('SELECT * FROM positions WHERE `state` = ? ', [1])->fetchAll();
        $sections = $this->db->select('SELECT * FROM sections WHERE `section_id` IS NULL ORDER BY id ASC')->fetchAll();
        $subSections = $this->db->select('SELECT * FROM sections WHERE `section_id` IS NOT NULL')->fetchAll();

        require_once(BASE_PATH . '/resources/views/app/employees/add-employee.php');
    }

    // store employee
    public function employeeStore($request)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        // check empty form
        if ($request['employee_name'] == '' || $request['phone'] == '' || $request['salary_price'] == '') {
            $this->flashMessage('error', _emptyInputs);
        }

        $existingEmployee = $this->db->select('SELECT * FROM employees WHERE `phone` = ?', [$request['phone']])->fetch();
        if ($existingEmployee) {
            $this->flashMessage('error', _phone_repeat);
        } else {

            if (!isset($request['password']) || strlen(trim($request['password'])) < 6) {
                $this->flashMessage('error', 'رمز عبور باید حداقل 6 کاراکتر داشته باشد.');
            }

            $request['password'] = $this->hash($request['password']);
            $employee = $this->validateInputs($request, ['image' => false]);

            $request['role'] = 1;

            // check image
            $this->handleImageUpload($request['image'], 'images/employees');

            try {
                $this->db->beginTransaction();

                // insert new employee
                $this->db->insert('employees', array_keys($request), $request);
                $lastId = $this->db->lastInsertId();

                $data = [
                    'section_name' => 'general',
                    'employee_id' => $lastId,
                ];
                $this->db->insert('permissions', array_keys($data), $data);

                $this->db->commit();

                $this->flashMessage('success', _success);
            } catch (Exception $e) {
                $this->db->rollBack();
                $this->flashMessage('error', 'خطا در ثبت اطلاعات: ' . $e->getMessage());
            }
        }
    }

    // show employees
    public function showEmployees()
    {
        $this->middleware(true, true, 'general');
        $employees = $this->getTableData('employees');
        require_once(BASE_PATH . '/resources/views/app/employees/show-employees.php');
        exit();
    }

    // edit employee page
    public function editEmployee($id)
    {
        $this->middleware(true, true, 'general', true);
        $branchId = $this->getBranchId();
        $employee = $this->db->select('SELECT * FROM employees WHERE id = ? AND branch_id = ?', [$id, $branchId])->fetch();
        $positions = $this->db->select('SELECT * FROM positions')->fetchAll();
        if ($employee != null) {
            require_once(BASE_PATH . '/resources/views/app/employees/edit-employee.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // edit employee store
    public function editEmployeeStore($request, $id)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        // check empty form
        if ($request['employee_name'] == '' || $request['phone'] == '' || $request['salary_price'] == '' || !isset($request['position'])) {
            $this->flashMessage('error', _emptyInputs);
        }

        $existEmployee = $this->db->select('SELECT * FROM employees WHERE `phone` = ?', [$request['phone']])->fetch();

        if ($existEmployee) {
            if ($id != $existEmployee['id']) {
                $this->flashMessage('error', 'شماره موبایل وارد شده قبلاً توسط کارمند دیگری ثبت شده است.');
                return;
            }
        }

        // check upload photo
        $this->updateImageUpload($request, 'image', 'employees', 'employees', $id);

        $this->db->update('employees', $id, array_keys($request), $request);
        $this->flashMessageTo('success', _success, url('employees'));
    }

    // employee detiles page
    public function employeeDetails($id)
    {
        $this->middleware(true, true, 'general');
        $branchId = $this->getBranchId();
        $employee = $this->db->select('SELECT * FROM employees WHERE id = ? AND branch_id = ?', [$id, $branchId])->fetch();
        $position = $this->db->select('SELECT * FROM positions WHERE id = ?', [$employee['position']])->fetch();

        $employee_salaries = $this->db->select(
            'SELECT * FROM salary_transactions WHERE employee_id = ? AND branch_id = ? AND `status` = ? ORDER BY month ASC, date ASC',
            [$id, $branchId, 1]
        )->fetchAll();

        if ($employee != null) {
            require_once(BASE_PATH . '/resources/views/app/employees/employee-details.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // change status employee
    public function changeStatusEmployee($id)
    {
        $this->middleware(true, true, 'general');
        $branchId = $this->getBranchId();
        $employee = $this->db->select('SELECT * FROM employees WHERE id = ? AND branch_id = ?', [$id, $branchId])->fetch();

        if (!$employee) {
            require_once BASE_PATH . '/404.php';
            exit;
        }

        $newStatus = $employee['state'] == 1 ? 2 : 1;

        $this->db->update('employees', $employee['id'], ['state'], [$newStatus]);
        $this->send_json_response(true, _success, $newStatus);
    }

    // live search
    public function searchEmployee($request)
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();

        $employees = $this->db->select(
            "SELECT id, employee_name
         FROM employees 
         WHERE employee_name LIKE ? 
           AND branch_id = ? 
         ORDER BY employee_name 
         LIMIT 20",
            ['%' . strtolower($request['search_term']) . '%', $branchId]
        )->fetchAll();

        $response = [
            'status' => 'success',
            'items' => $employees,
            'message' => 'lists'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
}
