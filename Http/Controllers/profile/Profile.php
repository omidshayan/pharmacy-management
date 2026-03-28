<?php

namespace App;

require_once 'Http/Controllers/App.php';

use database\DataBase;

class Profile extends App
{
    // profile page
    public function profile()
    {
        $this->middleware(true, true, 'general', true);
        $branchId = $this->getBranchId();
        $profile = $this->db->select('SELECT * FROM employees WHERE id = ? AND branch_id = ?', [$_SESSION['phy_employee']['id'], $branchId])->fetch();
        require_once(BASE_PATH . '/resources/views/app/profile/profile.php');
    }

    // change password
    public function changePasswordStore($request, $id)
    {
        $this->middleware(true, true, 'general', true, $request);

        if (empty($request['oldPassword']) || empty($request['newPassword']) || empty($request['newPasswordR'])) {
            $this->flashMessage('error', 'لطفا تمام قسمت های ضروری را وارد نمائید!');
        }
        if (strlen($request['newPassword']) < 8) {
            $this->flashMessage('error', 'تعداد کاراکترهای رمزعبور حداقل باید 8 کاراکتر باشد!');
        }

        if ($request['newPassword'] === $request['newPasswordR']) {
            $usre_id = $_SESSION['sk_em_id'];
            $request = $this->validateInputs($request);
            $branchId = $this->getBranchId();
            $user = $this->db->select('SELECT * FROM employees WHERE id =? AND branch_id = ?', [$usre_id, $branchId])->fetch();
            if (
                $user && password_verify($request['oldPassword'], $user['password'])
            ) {
                $request['newPassword'] = $this->hash($request['newPassword']);
                $this->db->update('employees', $usre_id, ['password'], [$request['newPassword']]);
                $this->flashMessage('success', 'رمزعبور شما با موفقیت تغییر کرد');
            } else {
                $this->flashMessage('error', 'رمزعبور فعلی درست نمی باشد!');
            }
        } else {
            $this->flashMessage('error', 'رمزعبور وارد شده با تکرار آن درست نمی باشد!');
        }
    }
}
