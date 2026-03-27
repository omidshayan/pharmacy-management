<?php

namespace App;

require_once 'Http/Controllers/App.php';

use database\DataBase;

class Setting extends App
{
    // management of years page
    public function manageYears()
    {
        $this->middleware(true, true, 'general', true);
        $years = $this->db->select('SELECT * FROM years ORDER BY id DESC')->fetchAll();
        require_once(BASE_PATH . '/resources/views/app/settings/manage-years.php');
    }

    // change status years
    public function changeStatusYears($request, $id)
    {
        $this->middleware(true, true, 'general', true, $request);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            $this->flashMessage('error', 'درخواست نامعتبر است!');
            exit();
        }

        $year = $this->db->select('SELECT * FROM years WHERE id = ?', [$id])->fetch();
        if (!$year) {
            http_response_code(404);
            $this->flashMessage('error', 'سال موردنظر یافت نشد!');
            exit();
        }

        $currentYear = $this->convertPersionNumber(jdate('Y'));
        $yearFromDB = is_numeric($year['year']) ? $year['year'] : jdate('Y', strtotime($year['year']));
        if ($currentYear == $yearFromDB) {
            http_response_code(403);
            $this->flashMessage('error', 'سال جاری را نمی‌توان بست!');
            exit();
        }

        $newStatus = ($year['status'] == 1) ? 2 : 1;
        $this->db->update('years', $year['id'], ['status'], [$newStatus]);
        $this->flashMessage('success', 'عملیات موفقانه انجام شد.');
    }

    // general settings
    public function generalSettings()
    {
        $this->middleware(true, true, 'general', true, $request);
        $settings = $this->db->select('SELECT * FROM settings')->fetch();
        require_once(BASE_PATH . '/resources/views/app/settings/settings.php');
    }

    // change status sale invoice
    public function changeStatusSaleInvoice()
    {
        $this->middleware(true, true, 'general');
        $row = $this->db->select('SELECT id, sell_any_situation FROM settings')->fetch();

        if (!$row) {
            require_once(BASE_PATH . '/404.php');
            exit();
        }

        $newStatus = ($row['sell_any_situation'] == 1) ? 2 : 1;
        $this->db->update('settings', $row['id'], ['sell_any_situation'], [$newStatus]);
        $this->send_json_response(true, _success, $newStatus);
    }

    // change status buy invoice
    public function changeStatusBuyInvoice()
    {
        $this->middleware(true, true, 'general');

        $row = $this->db->select('SELECT id, buy_any_situation FROM settings')->fetch();
        if (!$row) {
            require BASE_PATH . '/404.php';
            exit;
        }

        $new = ($row['buy_any_situation'] == 1) ? 2 : 1;

        $this->db->update('settings', $row['id'], ['buy_any_situation'], [$new]);
        $this->send_json_response(true, _success, $new);
    }

    // change status warehouse 
    public function changeStatusWarehouse()
    {
        $this->middleware(true, true, 'general');

        $row = $this->db->select('SELECT id, warehouse FROM settings')->fetch();

        $new = ($row['warehouse'] == 1) ? 2 : 1;

        $this->db->update('settings', $row['id'], ['warehouse'], [$new]);

        if (!isset($_SESSION['settings']) || !is_array($_SESSION['settings'])) {
            $_SESSION['settings'] = [];
        }

        $_SESSION['settings']['warehouse'] = $new;

        $this->send_json_response(true, _success, $new);
    }

    // change status Expiration 
    public function changeStatusExpiration()
    {
        $this->middleware(true, true, 'general');

        $row = $this->db->select('SELECT id, expiration_date FROM settings')->fetch();

        $new = ($row['expiration_date'] == 1) ? 2 : 1;

        $this->db->update('settings', $row['id'], ['expiration_date'], [$new]);

        if (!isset($_SESSION['settings']) || !is_array($_SESSION['settings'])) {
            $_SESSION['settings'] = [];
        }

        $_SESSION['settings']['expiration_date'] = $new;

        $this->send_json_response(true, _success, $new);
    }

    // change status HelpStatus 
    public function changeStatusHelpStatus()
    {
        $this->middleware(true, true, 'general');

        $row = $this->db->select('SELECT id, help_status FROM settings')->fetch();

        $new = ($row['help_status'] == 1) ? 2 : 1;

        $this->db->update('settings', $row['id'], ['help_status'], [$new]);

        if (!isset($_SESSION['settings']) || !is_array($_SESSION['settings'])) {
            $_SESSION['settings'] = [];
        }

        $_SESSION['settings']['help_status'] = $new;

        $this->send_json_response(true, _success, $new);
    }

    ////////// factor settings ////////////!SECTION

    // management of years page
    public function factorSettings()
    {
        $this->middleware(true, true, 'general', true);
        $factor_infos = $this->db->select('SELECT * FROM factor_settings')->fetch();
        require_once(BASE_PATH . '/resources/views/app/settings/factor-settings.php');
    }

    // factor settings store
    public function factorSettingsStore($request)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        if (empty($request['center_name'])) {
            $this->flashMessage('error', _emptyInputs);
        }

        $id = $this->getBranchId();

        $branch = $this->db->select('SELECT * FROM factor_settings WHERE branch_id = ?', [$id])->fetch();

        if (!$branch) {
            require_once(BASE_PATH . '/404.php');
            exit();
        }

        $this->updateImageUpload($request, 'image', 'public', 'factor_settings', $branch['id']);

        $this->db->update('factor_settings', $branch['id'], array_keys($request), $request);

        $this->flashMessage('success', 'اطلاعات با موفقیت ویرایش شد.');
    }
}
