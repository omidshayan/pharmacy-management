<?php

namespace App;

require_once 'Http/Controllers/App.php';

use database\DataBase;

class Position extends App
{
    // add position page
    public function Positions()
    {
        $this->middleware(true, true, 'general', true);
        $branchId = $this->getBranchId();
        $positions = $this->db->select('SELECT * FROM positions WHERE branch_id = ?', [$branchId])->fetchAll();
        require_once(BASE_PATH . '/resources/views/app/positions/positions.php');
    }

    // store postion
    public function store($request)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        if (empty(trim($request['name'] ?? ''))) {
            $this->flashMessage('error', _emptyInputs);
            return;
        }
        $branchId = $this->getBranchId();
        $position = $this->db->select('SELECT `name` FROM positions WHERE `name` = ? AND branch_id = ?', [$request['name'], $branchId])->fetch();

        if ($position) {
            $this->flashMessage('error', _repeat);
        } else {
            $this->db->insert('positions', array_keys($request), $request);
            $this->flashMessage('success', _success);
        }
    }

    // edit position page
    public function editPosition($id)
    {
        $this->middleware(true, true, 'general', true);

        $branchId = $this->getBranchId();
        $position = $this->db->select('SELECT * FROM positions WHERE id = ? AND branch_id = ?', [$id, $branchId])->fetch();

        if ($position) {
            require_once(BASE_PATH . '/resources/views/app/positions/edit-position.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // edit position store
    public function editPositionStore($request, $id)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        if ($request['name'] == '') {
            $this->flashMessage('error', _emptyInputs);
        }
        $branchId = $this->getBranchId();
        $position = $this->db->select('SELECT * FROM positions WHERE id = ? AND branch_id = ?', [$id, $branchId])->fetch();

        if ($position != null) {
            $this->db->update('positions', $id, array_keys($request), $request);
            $this->flashMessageTo('success', _success, url('positions'));
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // position detiles page
    public function positionDetails($id)
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();
        $position = $this->db->select('SELECT * FROM positions WHERE id = ? AND branch_id = ?', [$id, $branchId])->fetch();
        if ($position != null) {
            require_once(BASE_PATH . '/resources/views/app/positions/position-details.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // change status Position
    public function changeStatusPosition($id)
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();
        $position = $this->db->select('SELECT * FROM positions WHERE id = ? AND branch_id = ?', [$id, $branchId])->fetch();

        if (!$position) {
            require BASE_PATH . '/404.php';
            exit;
        }

        $newState = $position['state'] == 1 ? 2 : 1;
        $this->db->update('positions', $position['id'], ['state'], [$newState]);
        $this->send_json_response(true, _success, $newState);
    }
}
