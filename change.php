<?php

        $companies = $this->db->select("SELECT id, company_name FROM companies WHERE `status` = ? AND branch_id = ?", [1, $branchId])->fetchAll();


            public function productCategories()
    {
        $this->middleware(true, true, 'general', true);

        $branchId = $this->getBranchId();

        $products_categories = $this->db->select('SELECT * FROM product_cat WHERE branch_id = ?', [$branchId])->fetchAll();

        require_once(BASE_PATH . '/resources/views/app/products/products-categories/products-categories.php');
    }

