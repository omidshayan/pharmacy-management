<?php

namespace App;

class Attribute extends App
{
    // attributes page
    public function attributes()
    {
        $this->middleware(true, true, 'general', true);

        $branchId = $this->getBranchId();

        $attributes = $this->db->select('SELECT * FROM attributes WHERE branch_id = ? ORDER BY id DESC', [$branchId])->fetchAll();

        require_once(BASE_PATH . '/resources/views/app/basic-sections/attributes/attributes.php');
    }

    // Attribute store
    public function attributeStore($request)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        if ($request['att_name'] == '' || $request['att_type'] == '') {
            $this->flashMessage('error', _emptyInputs);
        }

        $attribute = $this->db->select('SELECT att_name FROM attributes WHERE `att_name` = ?', [$request['att_name']])->fetch();

        if (!empty($attribute['att_name'])) {
            $this->flashMessage('error', _repeat);
        } else {
            $this->db->insert('attributes', array_keys($request), $request);
            $this->flashMessage('success', _success);
        }
    }

    // edit Attribute page
    public function editAttribute($id)
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();

        $item = $this->db->select('SELECT * FROM attributes WHERE `id` = ? AND branch_id = ?', [$id, $branchId])->fetch();
        if ($item != null) {
            require_once(BASE_PATH . '/resources/views/app/basic-sections/attributes/edit-attribute.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // edit company Store
    public function editAttributeStore($request, $id)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        // check empty form
        if ($request['att_name'] == '' || $request['att_type'] == '') {
            $this->flashMessage('error', _emptyInputs);
        }

        $branchId = $this->getBranchId();

        $item = $this->db->select('SELECT * FROM attributes WHERE `att_name` = ? AND branch_id = ?', [$request['att_name'], $branchId])->fetch();

        if ($item) {
            if ($item['id'] != $id) {
                $this->flashMessage('error', 'نام وارد شده تکراری است.');
            }
        }
        $this->db->update('attributes', $id, array_keys($request), $request);
        $this->flashMessageTo('success', _success, url('attributes'));
    }

    // attribute Details detiles page
    public function attributeDetails($id)
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();

        $item = $this->db->select('SELECT * FROM attributes WHERE `id` = ? AND branch_id = ?', [$id, $branchId])->fetch();

        if ($item != null) {
            require_once(BASE_PATH . '/resources/views/app/basic-sections/attributes/attribute-details.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // change status Company
    public function changeStatusAttribute($id)
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();
        $item = $this->db->select('SELECT * FROM attributes WHERE id = ? AND branch_id = ?', [$id, $branchId])->fetch();

        if (!$item) {
            require BASE_PATH . '/404.php';
            exit;
        }

        $newState = $item['status'] == 1 ? 2 : 1;
        $this->db->update('attributes', $item['id'], ['status'], [$newState]);
        $this->send_json_response(true, _success, $newState);
    }
}
