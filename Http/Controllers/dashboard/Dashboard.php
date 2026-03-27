<?php

namespace App;

require_once 'Http/Controllers/App.php';

use database\DataBase;

class Dashboard extends App
{
    public function index()
    {
        $this->validateUserBranch();
        $this->middleware(true, true, 'general', true);
        $this->db = DataBase::getInstance();

        require_once(BASE_PATH . '/resources/views/app/dashboard/index.php');
    }
}
