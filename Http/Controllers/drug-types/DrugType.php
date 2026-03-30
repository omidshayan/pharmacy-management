<?php

namespace App;

require_once 'Http/Controllers/App.php';

class DrugType extends App
{
    // ProductUnit page
    public function drugTypes()
    {
        $this->middleware(true, true, 'general', true);

        $drug_types = $this->db->select('SELECT * FROM drug_types')->fetchAll();

        require_once(BASE_PATH . '/resources/views/app/products/drug-types/drug-types.php');
    }

    // productsUnitStore Store
    public function drugTypeStore($request)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        if (empty($request['type_name']) || empty($request['branch_id'])) {
            $this->flashMessage('error', _emptyInputs);
        }

        $item = $this->db->select('SELECT type_name FROM drug_types WHERE `type_name` = ?', [$request['type_name']])->fetch();

        if (!empty($item['type_name'])) {
            $this->flashMessage('error', _repeat);
        } else {
            $this->db->insert('drug_types', array_keys($request), $request);
            $this->flashMessage('success', _success);
        }
    }

    // product Cat Details detiles page
    public function drugTypeDetails($id)
    {
        $this->middleware(true, true, 'general');

        $item = $this->db->select('SELECT * FROM drug_types WHERE `id` = ?', [$id])->fetch();

        if ($item != null) {
            require_once(BASE_PATH . '/resources/views/app/products/drug-types/drug-type-details.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // edit expense category page
    public function editDrugType($id)
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();

        $item = $this->db->select('SELECT * FROM drug_types WHERE `id` = ? AND branch_id = ?', [$id, $branchId])->fetch();
        if ($item != null) {
            require_once(BASE_PATH . '/resources/views/app/products/drug-types/edit-drug-type.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    //  edit Product Unit Store
    public function editDrugTypeStore($request, $id)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        // check empty form
        if (empty($request['type_name']) || empty($request['branch_id'])) {
            $this->flashMessage('error', _emptyInputs);
        }

        $branchId = $this->getBranchId();

        $item = $this->db->select('SELECT id, type_name FROM drug_types WHERE `type_name` = ? AND branch_id = ?', [$request['type_name'], $branchId])->fetch();

        if ($item) {
            if ($item['id'] != $id) {
                $this->flashMessage('error', 'نام وارد شده تکراری است.');
            }
        }
        $this->db->update('drug_types', $id, array_keys($request), $request);
        $this->flashMessageTo('success', _success, url('drug-types'));
    }

    // change status product Cat
    public function changeStatusDrugType($id)
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();
        $item = $this->db->select('SELECT id, `status` FROM drug_types WHERE id = ? AND branch_id = ?', [$id, $branchId])->fetch();

        if (!$item) {
            require BASE_PATH . '/404.php';
            exit;
        }

        $newState = $item['status'] == 1 ? 2 : 1;
        $this->db->update('drug_types', $item['id'], ['status'], [$newState]);
        $this->send_json_response(true, _success, $newState);
    }
}
