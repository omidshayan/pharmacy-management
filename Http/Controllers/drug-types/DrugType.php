<?php

namespace App;

require_once 'Http/Controllers/App.php';

class DrugType extends App
{
    // ProductUnit page
    public function drugTypes()
    {
        $this->middleware(true, true, 'general', true);

        $drug_types = $this->db->select('SELECT * FROM drug_types WHERE `status` = ? ', [1])->fetchAll();

        require_once(BASE_PATH . '/resources/views/app/drug-types/drug-types.php');
    }

    // productsUnitStore Store
    public function productsUnitStore($request)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        if (empty($request['product_unit']) || empty($request['branch_id'])) {
            $this->flashMessage('error', _emptyInputs);
        }

        $item = $this->db->select('SELECT product_unit FROM products_units WHERE `product_unit` = ?', [$request['product_unit']])->fetch();

        if (!empty($item['product_unit'])) {
            $this->flashMessage('error', _repeat);
        } else {
            $this->db->insert('products_units', array_keys($request), $request);
            $this->flashMessage('success', _success);
        }
    }

    // product Cat Details detiles page
    public function productUnitDetails($id)
    {
        $this->middleware(true, true, 'general');

        $item = $this->db->select('SELECT * FROM products_units WHERE `id` = ?', [$id])->fetch();

        if ($item != null) {
            require_once(BASE_PATH . '/resources/views/app/products/products-units/products-unit-details.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // edit expense category page
    public function editProductUnit($id)
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();

        $item = $this->db->select('SELECT * FROM products_units WHERE `id` = ? AND branch_id = ?', [$id, $branchId])->fetch();
        if ($item != null) {
            require_once(BASE_PATH . '/resources/views/app/products/products-units/edit-product-unit.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    //  edit Product Unit Store
    public function editProductUnitStore($request, $id)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        // check empty form
        if (empty($request['product_unit']) || empty($request['branch_id'])) {
            $this->flashMessage('error', _emptyInputs);
        }

        $branchId = $this->getBranchId();

        $item = $this->db->select('SELECT id, product_unit FROM products_units WHERE `product_unit` = ? AND branch_id = ?', [$request['product_unit'], $branchId])->fetch();

        if ($item) {
            if ($item['id'] != $id) {
                $this->flashMessage('error', 'نام وارد شده تکراری است.');
            }
        }
        $this->db->update('products_units', $id, array_keys($request), $request);
        $this->flashMessageTo('success', _success, url('products-units'));
    }

    // change status product Cat
    public function changeStatusProductUnit($id)
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();
        $item = $this->db->select('SELECT id, `status` FROM products_units WHERE id = ? AND branch_id = ?', [$id, $branchId])->fetch();

        if (!$item) {
            require BASE_PATH . '/404.php';
            exit;
        }

        $newState = $item['status'] == 1 ? 2 : 1;
        $this->db->update('products_units', $item['id'], ['status'], [$newState]);
        $this->send_json_response(true, _success, $newState);
    }
}
