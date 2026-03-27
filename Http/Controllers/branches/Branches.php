<?php

namespace App;

require_once 'Http/Controllers/App.php';


use Models\Calendar\Calendar;

class Branches extends App
{
    private $calendar;

    public function __construct()
    {
        parent::__construct();
        $this->calendar = new Calendar();
    }

    // manage branches
    public function branches()
    {
        $this->middleware(true, true, 'students', true);
        $branches = $this->db->select('SELECT * FROM branches WHERE `is_active` = 1 ORDER BY id ASC')->fetchAll();
        require_once(BASE_PATH . '/resources/views/app/branches/branches.php');
    }

    // branch store
    public function branchStore($request)
    {
        $this->middleware(true, true, 'students', true, $request, true);
        if ($request['branch_name'] == '') {
            $this->flashMessage('error', _emptyInputs);
        }
        $request = $this->validateInputs($request);

        $branch_name = $this->db->select('SELECT branch_name FROM branches WHERE `branch_name` = ?', [$request['branch_name']])->fetch();
       
        if (!empty($branch_name['branch_name'])) {
            $this->flashMessage('error', _repeat);
        } else {

            $this->db->insert('branches', array_keys($request), $request);

            $branch_id = $this->db->lastInsertId();

            $branch_info = [
                'name'      => $request['branch_name'],
                'who_it'    => $request['who_it'],
                'branch_id' => $branch_id
            ];

            $this->db->insert('financial_summary', ['branch_id'], [$branch_id]);
            $this->db->insert('cash_drawer', array_keys($branch_info), $branch_info);
            $this->flashMessage('success', _success);
        }
    }













    // edit product category page
    public function editProductCat($id)
    {
        dd('ok');
    }

    // product Cat Details detiles page
    public function productCatDetails($id)
    {
        $this->middleware(true, true, 'students');
        $product_cat = $this->db->select('SELECT * FROM product_cat WHERE `id` = ?', [$id])->fetch();
        if ($product_cat != null) {
            require_once(BASE_PATH . '/resources/views/app/products/products-categories/product-cat-details.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // change status product Cat
    public function changeStatusProductCat($id)
    {
        $this->middleware(true, true, 'students');
        $product_categories = $this->db->select('SELECT * FROM product_cat WHERE id = ?', [$id])->fetch();
        if ($product_categories != null) {
            if ($product_categories['status'] == 1) {
                $this->db->update('product_cat', $product_categories['id'], ['status'], [2]);
                $this->send_json_response(true, _success, 2);
            } else {
                $this->db->update('product_cat', $product_categories['id'], ['status'], [1]);
                $this->send_json_response(true, _success, 1);
            }
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }
}
