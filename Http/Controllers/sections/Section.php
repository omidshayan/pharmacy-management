<?php

namespace App;

require_once 'Http/Controllers/App.php';

use database\DataBase;

class Section extends App
{

    // Section page
    public function index()
    {
        $this->middleware(true, true, 'students');
        $sections = $this->db->select('SELECT * FROM sections')->fetchAll();
        require_once(BASE_PATH . '/resources/views/app/sections/index.php');
    }

    // packages store
    public function sectionStore($request)
    {
        $this->middleware(true, true, 'students', true, $request, true);
        if ($request['name'] == '') {
            $this->flashMessage('error', _emptyInputs);
        }
        $request['name'] = $this->validation($request['name']);
        $packages = $this->db->select('SELECT * FROM sections WHERE `name` = ?', [$request['name']])->fetch();
        if ($packages > 0) {
            $this->flashMessage('error', _repeat);
        } else {
            $this->db->insert('sections', array_keys($request), $request);
            $this->flashMessage('success', _success);
        }
    }
}
