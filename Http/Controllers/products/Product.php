<?php

namespace App;

require_once 'Http/Controllers/App.php';

class Product extends App
{
    // add expense page
    public function addProduct()
    {
        $this->middleware(true, true, 'general', true);

        $branchId = $this->getBranchId();

        $product_cats = $this->db->select("SELECT * FROM product_cat WHERE `status` = ? AND branch_id = ?", [1, $branchId])->fetchAll();

        $products_units = $this->db->select("SELECT * FROM products_units WHERE `status` = ? AND branch_id = ?", [1, $branchId])->fetchAll();

        $companies = $this->db->select("SELECT id, company_name FROM companies WHERE `status` = ?", [1])->fetchAll();

        $checkboxAtts = $this->db->select(
            "SELECT id, att_name FROM attributes 
            WHERE status = ? AND branch_id = ? AND att_type = ?",
            [1, $branchId, 'checkbox']
        )->fetchAll();

        $textAtts = $this->db->select(
            "SELECT id, att_name FROM attributes 
            WHERE status = ? AND branch_id = ? AND att_type = ?",
            [1, $branchId, 'text']
        )->fetchAll();

        require_once(BASE_PATH . '/resources/views/app/products/add-product.php');
    }

    // store product
    public function productStore($request)
    {
        // dd($request);
        $this->middleware(true, true, 'general', true, $request, true);

        if (empty($request['product_name']) || empty($request['quantity_in_pack']) || !isset($request['product_cat']) || !isset($request['package_type'])) {
            $this->flashMessage('error', _emptyInputs);
        }

        if ($request['quantity_in_pack'] > 1 && empty($request['unit_type'])) {
            $this->flashMessage('error', 'لطفا واحد کوچکتر را انتخاب نمائید!');
        }

        $priceFields = ['package_price_buy', 'package_price_sell'];
        foreach ($priceFields as $field) {
            if (!isset($request[$field]) || !is_numeric($request[$field]) || $request[$field] <= 0) {
                $this->flashMessage('error', 'قیمت‌ها باید عددی مثبت باشند');
            }
        }

        if ($request['package_price_sell'] < $request['package_price_buy']) {
            $this->flashMessage('error', 'قیمت فروش نباید کمتر از قیمت خرید باشد');
        }

        if (!is_numeric($request['quantity_in_pack']) || $request['quantity_in_pack'] <= 0) {
            $this->flashMessage('error', 'تعداد بسته‌ها باید یک عدد مثبت باشد');
        }

        if (!empty($request['product_image'])) {
            $this->validateInputs($request, ['product_image' => false]);
            $request['product_image'] = $this->handleImageUpload($request['product_image'], 'images/products');
        }

        $branchId = $this->getBranchId();

        // check unit price
        if ((int)$request['quantity_in_pack'] > 1) {

            if (
                !isset($request['unit_price_buy']) || !is_numeric($request['unit_price_buy']) || $request['unit_price_buy'] <= 0 ||
                !isset($request['unit_price_sell']) || !is_numeric($request['unit_price_sell']) || $request['unit_price_sell'] <= 0
            ) {
                $this->flashMessage('error', 'لطفاً قیمت واحد کوچکتر را به‌درستی وارد نمائید!');
            }

            if ($request['unit_price_sell'] < $request['unit_price_buy']) {
                $this->flashMessage('error', 'قیمت فروش واحد نباید کمتر از قیمت خرید واحد باشد');
            }
        }

        if ($branchId === 'ALL') {
            if (empty($request['branch_id']) || !is_numeric($request['branch_id'])) {
                $this->flashMessage('error', 'لطفاً شعبه‌ای را انتخاب کنید');
            }

            $branchCheck = $this->db->select("SELECT id FROM branches WHERE id = ? AND is_active = 1 LIMIT 1", [$request['branch_id']])->fetch();
            if (!$branchCheck) {
                $this->flashMessage('error', 'شعبه انتخاب‌شده معتبر یا فعال نیست');
            }

            $branchId = (int)$request['branch_id'];
        }

        $request['branch_id'] = $branchId;

        $exists = $this->db->select(
            "SELECT id FROM products WHERE branch_id = ? AND product_name = ? LIMIT 1",
            [$branchId, $request['product_name']]
        )->fetch();

        if ($exists) {
            $this->flashMessage('error', 'این دوا قبلاً در این شعبه ثبت شده است');
        }

        // check for attributes
        $attributes = [];
        if (isset($request['attributes']) && is_array($request['attributes'])) {

            $attributes = $request['attributes'];

            unset($request['attributes']);
        }

        // check quantity 
        if ((int)$request['quantity_in_pack'] === 1) {
            $request['unit_price_buy']  = null;
            $request['unit_price_sell'] = null;
        }

        $this->db->insert('products', array_keys($request), $request);
        $productId = $this->db->lastInsertId();

        // insert attributes
        foreach ($attributes as $attributeId => $value) {
            if ($value === '' || $value === null) {
                continue;
            }
            $this->db->insert(
                'attribute_values',
                ['product_id', 'attribute_id', 'value'],
                [
                    $productId,
                    $attributeId,
                    $value
                ]
            );
        }

        $this->flashMessage('success', _success);
    }

    // show products
    public function showProducts()
    {
        $this->middleware(true, true, 'general');
        $products = $this->getTableData('products');
        require_once(BASE_PATH . '/resources/views/app/products/show-products.php');
        exit();
    }

    // edit product page
    public function editProduct($id)
    {
        $this->middleware(true, true, 'general', true);

        $branchId = $this->getBranchId();

        $product = $this->db->select('SELECT * FROM products WHERE `id` = ?', [$id])->fetch();
        $product_cats = $this->db->select('SELECT * FROM product_cat WHERE `status` = 1')->fetchAll();
        $products_units = $this->db->select('SELECT * FROM products_units WHERE `status` = 1')->fetchAll();
        if ($product != null) {

            $checkboxAtts = $this->db->select(
                "SELECT id, att_name FROM attributes 
            WHERE `status` = ? AND branch_id = ? AND att_type = ?",
                [1, $branchId, 'checkbox']
            )->fetchAll();

            $textAtts = $this->db->select(
                "SELECT id, att_name FROM attributes 
            WHERE `status` = ? AND branch_id = ? AND att_type = ?",
                [1, $branchId, 'text']
            )->fetchAll();

            $attValues = $this->db->select(
                "SELECT attribute_id, value FROM attribute_values 
                WHERE product_id = ?",
                [$id]
            )->fetchAll();

            // تبدیل به آرایه ساده برای دسترسی راحت
            $attValuesMap = [];
            foreach ($attValues as $val) {
                $attValuesMap[$val['attribute_id']] = $val['value'];
            }

            $unitPrices = $this->calculateUnitPrices($product);

            require_once(BASE_PATH . '/resources/views/app/products/edit-product.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // edit expense store
    public function editProductStore($request, $id)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        if (empty($request['product_name']) || empty($request['quantity_in_pack']) || !isset($request['product_cat']) || !isset($request['package_type'])) {
            $this->flashMessage('error', _emptyInputs);
        }

        if ($request['quantity_in_pack'] > 1 && empty($request['unit_type'])) {
            $this->flashMessage('error', 'لطفا واحد کوچکتر را انتخاب نمائید!');
        }

        $priceFields = ['package_price_buy', 'package_price_sell'];
        foreach ($priceFields as $field) {
            if (!isset($request[$field]) || !is_numeric($request[$field]) || $request[$field] <= 0) {
                $this->flashMessage('error', 'قیمت‌ها باید عددی مثبت باشند');
            }
        }

        if ($request['package_price_sell'] < $request['package_price_buy']) {
            $this->flashMessage('error', 'قیمت فروش نباید کمتر از قیمت خرید باشد');
        }

        if (!is_numeric($request['quantity_in_pack']) || $request['quantity_in_pack'] <= 0) {
            $this->flashMessage('error', 'تعداد بسته‌ها باید یک عدد مثبت باشد');
        }

        if (!empty($request['product_image'])) {
            $this->validateInputs($request, ['product_image' => false]);
            $request['product_image'] = $this->handleImageUpload($request['product_image'], 'images/products');
        } else {
            unset($request['product_image']);
        }

        $branchId = $this->getBranchId();

        // check unit price
        if ((int)$request['quantity_in_pack'] > 1) {

            if (
                !isset($request['unit_price_buy']) || !is_numeric($request['unit_price_buy']) || $request['unit_price_buy'] <= 0 ||
                !isset($request['unit_price_sell']) || !is_numeric($request['unit_price_sell']) || $request['unit_price_sell'] <= 0
            ) {
                $this->flashMessage('error', 'لطفاً قیمت واحد کوچکتر را به‌درستی وارد نمائید!');
            }

            if ($request['unit_price_sell'] < $request['unit_price_buy']) {
                $this->flashMessage('error', 'قیمت فروش واحد نباید کمتر از قیمت خرید واحد باشد');
            }
        }

        if ($branchId === 'ALL') {
            if (empty($request['branch_id']) || !is_numeric($request['branch_id'])) {
                $this->flashMessage('error', 'لطفاً شعبه‌ای را انتخاب کنید');
            }

            $branchCheck = $this->db->select(
                "SELECT id FROM branches WHERE id = ? AND is_active = 1 LIMIT 1",
                [$request['branch_id']]
            )->fetch();

            if (!$branchCheck) {
                $this->flashMessage('error', 'شعبه انتخاب‌شده معتبر یا فعال نیست');
            }

            $branchId = (int)$request['branch_id'];
        }

        $request['branch_id'] = $branchId;

        $exists = $this->db->select(
            "SELECT id FROM products WHERE branch_id = ? AND product_name = ? AND id != ? LIMIT 1",
            [$branchId, $request['product_name'], $id]
        )->fetch();

        if ($exists) {
            $this->flashMessage('error', 'این دوا قبلاً در این شعبه ثبت شده است');
        }

        // attributes
        $attributes = [];
        if (isset($request['attributes']) && is_array($request['attributes'])) {
            $attributes = $request['attributes'];
            unset($request['attributes']);
        }

        // check quantity 
        if ((int)$request['quantity_in_pack'] === 1) {
            $request['unit_price_buy']  = null;
            $request['unit_price_sell'] = null;
        }

        // update product
        $this->db->update('products', $id, array_keys($request), $request);

        $this->db->deleteWhere('attribute_values', 'product_id', $id);

        foreach ($attributes as $attributeId => $value) {
            if ($value === '' || $value === null) {
                continue;
            }

            $this->db->insert(
                'attribute_values',
                ['product_id', 'attribute_id', 'value'],
                [
                    $id,
                    $attributeId,
                    $value
                ]
            );
        }

        $this->flashMessageTo('success', _success, url('products'));
    }

    // expense detiles page
    public function productDetails($id)
    {
        dd('ol');
        $this->middleware(true, true, 'general');
        $product = $this->db->select('SELECT p.*, i.quantity FROM products p LEFT JOIN inventory i ON i.product_id = p.id WHERE p.id = ?', [$id])->fetch();

        $branch = $this->db->select('SELECT branch_name FROM branches WHERE id = ?', [$product['branch_id']])->fetch();
        if ($product != null) {
            $unitPrices = $this->calculateUnitPrices($product);
            require_once(BASE_PATH . '/resources/views/app/products/product-details.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // change status product
    public function changeStatusProduct($id)
    {
        $this->middleware(true, true, 'general');

        $category = $this->db->select('SELECT * FROM products WHERE id = ?', [$id])->fetch();

        if (!$category) {
            require_once BASE_PATH . '/404.php';
            exit;
        }

        $newStatus = $category['status'] == 1 ? 2 : 1;

        $this->db->update('products', $category['id'], ['status'], [$newStatus]);
        $this->send_json_response(true, _success, $newStatus);
    }

    // live search for show details
    public function searchProduct($request)
    {
        $this->middleware(true, true, 'general');

        $keyword = '%' . $request['customer_name'] . '%';
        $branchId = $this->getBranchId();

        if ($branchId === 'ALL') {
            $sql = "SELECT * FROM products WHERE product_name LIKE ? LIMIT 20";
            $params = [$keyword];
        } else {
            $sql = "SELECT * FROM products WHERE product_name LIKE ? AND branch_id = ? LIMIT 20";
            $params = [$keyword, $branchId];
        }

        $infos = $this->db->select($sql, $params)->fetchAll();

        $response = [
            'status' => 'success',
            'items'  => $infos,
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    ////////////////////////// categories products /////////////////////////////////

    //  product page
    public function productCategories()
    {
        $this->middleware(true, true, 'general', true);

        $products_categories = $this->db->select('SELECT * FROM product_cat WHERE `status` = ? ', [1])->fetchAll();

        require_once(BASE_PATH . '/resources/views/app/products/products-categories/products-categories.php');
    }

    // store product
    public function productCatStore($request)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        $branchId = $this->getBranchId();

        if (empty($request['product_cat_name'])) {
            $this->flashMessage('error', _emptyInputs);
        }

        $existingLocal = $this->db->select(
            'SELECT * FROM product_cat WHERE product_cat_name = ? AND branch_id = ?',
            [$request['product_cat_name'], $branchId]
        )->fetch();

        if (!empty($existingLocal)) {
            $this->flashMessage('error', _repeat);
        }

        $this->db->insert('product_cat', array_keys($request), $request);
        $this->flashMessage('success', _success);
    }

    // edit product category page
    public function editProductCat($id)
    {
        $this->middleware(true, true, 'general');
        $cat = $this->db->select('SELECT * FROM product_cat WHERE `id` = ?', [$id])->fetch();
        if ($cat != null) {
            require_once(BASE_PATH . '/resources/views/app/products/products-categories/edit-cat.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // edit Product Cat Store
    public function editProductCatStore($request, $id)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        // check empty form
        if ($request['product_cat_name'] == '') {
            $this->flashMessage('error', _emptyInputs);
        }

        $branchId = $this->getBranchId();

        $item = $this->db->select('SELECT * FROM product_cat WHERE `product_cat_name` = ? AND branch_id = ?', [$request['product_cat_name'], $branchId])->fetch();

        if ($item) {
            if ($item['id'] != $id) {
                $this->flashMessageTo('error', 'نام دسته بندی وارد شده تکراری است.', url('product-categories'));
                return;
            }
        }
        $this->db->update('product_cat', $id, array_keys($request), $request);
        $this->flashMessageTo('success', _success, url('product-categories'));
    }

    // product Cat Details detiles page
    public function productCatDetails($id)
    {
        $this->middleware(true, true, 'general');
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
        $this->middleware(true, true, 'general');

        $category = $this->db->select('SELECT * FROM product_cat WHERE id = ?', [$id])->fetch();

        if (!$category) {
            require_once BASE_PATH . '/404.php';
            exit;
        }

        $newStatus = $category['status'] == 1 ? 2 : 1;

        $this->db->update('product_cat', $category['id'], ['status'], [$newStatus]);
        $this->send_json_response(true, _success, $newStatus);
    }

    // inventories
    public function inventories()
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();

        $setting = $this->db->select(
            "SELECT warehouse FROM settings WHERE branch_id = ?",
            [$branchId]
        )->fetch();

        $hasWarehouse = isset($setting['warehouse']) && $setting['warehouse'] == 1;

        $warehouses = [];

        if ($hasWarehouse) {
            $warehouses = $this->db->select(
                "SELECT id, warehouse_name
             FROM warehouses
             WHERE is_active = 1 AND branch_id = ?",
                [$branchId]
            )->fetchAll();
        }

        $inventoryRows = $this->db->select("
            SELECT
                i.product_id,
                i.product_name,
                i.warehouse_id,
                i.quantity,
                p.package_type,
                p.unit_type
            FROM inventory AS i
            LEFT JOIN products AS p
                ON p.id = i.product_id
            WHERE i.branch_id = ?
            ORDER BY i.product_name ASC
        ", [$branchId])->fetchAll();

        $products = [];

        foreach ($inventoryRows as $row) {

            $productId = $row['product_id'];

            if (!isset($products[$productId])) {
                $products[$productId] = [
                    'id'             => $productId,
                    'product_name'   => $row['product_name'],
                    'package_type'   => $row['package_type'] ?? null,
                    'unit_type'      => $row['unit_type'] ?? null,
                    'total_quantity' => 0,
                    'warehouses'     => []
                ];
            }

            $products[$productId]['total_quantity'] += (float)$row['quantity'];

            if ($hasWarehouse) {
                $warehouseId = $row['warehouse_id'] ?? 0;

                $products[$productId]['warehouses'][$warehouseId] =
                    ($products[$productId]['warehouses'][$warehouseId] ?? 0)
                    + (float)$row['quantity'];
            }
        }

        $inventories = array_values($products);

        require_once(BASE_PATH . '/resources/views/app/product-inventory/inventories.php');
    }


    // change status product
    // public function changeStatusExpense($id)
    // {
    //     $this->middleware(true, true, 'change-status-package');
    //     $expense = $this->db->select('SELECT * FROM expenses WHERE id = ?', [$id])->fetch();
    //     if ($expense != null) {
    //         if ($expense['status'] == 1) {
    //             $this->db->update('expenses', $expense['id'], ['status'], [2]);
    //             $this->send_json_response(true, _success, 2);
    //         } else {
    //             $this->db->update('expenses', $expense['id'], ['status'], [1]);
    //             $this->send_json_response(true, _success, 1);
    //         }
    //     } else {
    //         require_once BASE_PATH . '/404.php';
    //         exit();
    //     }
    // }

}
