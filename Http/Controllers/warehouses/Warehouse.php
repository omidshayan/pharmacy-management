<?php

namespace App;

require_once 'Http/Controllers/App.php';


use Models\Calendar\Calendar;

class Warehouse extends App
{
    private $calendar;

    public function __construct()
    {
        parent::__construct();
        $this->calendar = new Calendar();
    }

    // managment warehouses page
    public function warehouses()
    {
        dd('ok');
        $this->middleware(true, true, 'general', true);
        $warehouses = $this->db->select('SELECT * FROM warehouses ORDER BY id DESC')->fetchAll();
        $employees = $this->db->select('SELECT * FROM employees')->fetchAll();
        require_once(BASE_PATH . '/resources/views/app/warehouses/warehouses.php');
    }

    // store product
    public function warehouseStore($request)
    {
        dd('ok');
        $this->middleware(true, true, 'students', true, $request, true);
        if ($request['product_cat_name'] == '') {
            $this->flashMessage('error', _emptyInputs);
        }

        $expenses_categories = $this->db->select('SELECT * FROM product_cat WHERE `product_cat_name` = ?', [$request['product_cat_name']])->fetch();
        if (!empty($expenses_categories['product_cat_name'])) {
            $this->flashMessage('error', _repeat);
        } else {
            $this->db->insert('product_cat', array_keys($request), $request);
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