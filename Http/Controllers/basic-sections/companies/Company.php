<?php

namespace App;

class Company extends App
{
    // Companies page
    public function companies()
    {
        $this->middleware(true, true, 'general', true);

        $branchId = $this->getBranchId();

        $companies = $this->db->select('SELECT * FROM companies WHERE branch_id = ? ORDER BY id DESC', [$branchId])->fetchAll();

        require_once(BASE_PATH . '/resources/views/app/basic-sections/companies/companies.php');
    }

    // store company
    public function companyStore($request)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        if ($request['company_name'] == '') {
            $this->flashMessage('error', _emptyInputs);
        }

        $company = $this->db->select('SELECT company_name FROM companies WHERE `company_name` = ?', [$request['company_name']])->fetch();

        if (!empty($company['company_name'])) {
            $this->flashMessage('error', _repeat);
        } else {
            $this->db->insert('companies', array_keys($request), $request);
            $this->flashMessage('success', _success);
        }
    }

    // edit companies page
    public function editCompany($id)
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();

        $company = $this->db->select('SELECT * FROM companies WHERE `id` = ? AND branch_id = ?', [$id, $branchId])->fetch();
        if ($company != null) {
            require_once(BASE_PATH . '/resources/views/app/basic-sections/companies/edit-company.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // edit company Store
    public function editCompanyStore($request, $id)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        // check empty form
        if ($request['company_name'] == '') {
            $this->flashMessage('error', _emptyInputs);
        }

        $branchId = $this->getBranchId();

        $item = $this->db->select('SELECT * FROM companies WHERE `company_name` = ? AND branch_id = ?', [$request['company_name'], $branchId])->fetch();

        if ($item) {
            if ($item['id'] != $id) {
                $this->flashMessage('error', 'نام کمپانی وارد شده تکراری است.');
            }
        }
        $this->db->update('companies', $id, array_keys($request), $request);
        $this->flashMessageTo('success', _success, url('companies'));
    }

    // Expense Cat Details detiles page
    public function companyDetails($id)
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();

        $company = $this->db->select('SELECT * FROM companies WHERE `id` = ? AND branch_id = ?', [$id, $branchId])->fetch();

        if ($company != null) {
            require_once(BASE_PATH . '/resources/views/app/basic-sections/companies/company-details.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // change status Company
    public function changeStatusCompany($id)
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();
        $item = $this->db->select('SELECT * FROM companies WHERE id = ? AND branch_id = ?', [$id, $branchId])->fetch();

        if (!$item) {
            require BASE_PATH . '/404.php';
            exit;
        }

        $newState = $item['status'] == 1 ? 2 : 1;
        $this->db->update('companies', $item['id'], ['status'], [$newState]);
        $this->send_json_response(true, _success, $newState);
    }
}
