<?php

        $companies = $this->db->select("SELECT id, company_name FROM companies WHERE `status` = ? AND branch_id = ?", [1, $branchId])->fetchAll();
