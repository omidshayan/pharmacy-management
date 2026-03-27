<?php

namespace Auth;

require_once 'Http/Auth/Auth.php';

use Auth\Auth;
use database\Database;

class AdminLogin extends Auth
{
    // login page
    public function adminLogin()
    {

        if ((isset($_SESSION['er_admin_id']) && $_SESSION['er_admin_id'] != '') || isset($_SESSION['er_admin_name']) && $_SESSION['er_admin_name'] != '') {
            $this->redirect('/');
            exit();
        } else {
            require_once(BASE_PATH . '/resources/views/auth/admin-login.php');
            exit();
        }
    }

    // check for login
    public function adminCheckLogin($request)
    {
        if ($request['phone'] == '' || $request['password'] == '') {
            flash('error', 'لطفا تمام اطلاعات را وارد نمایید');
            $this->redirectBack();
            exit();
        }
        $db = new Database();
        $user = $db->select("SELECT * FROM `admin_sis` WHERE phone = ? ", [$request['phone']])->fetch();
        if ($user != null && $user > 0) {
            if (password_verify($request['password'], $user['password'])) {
                if ($user['state'] == 2 && $user['state'] != 0 && $user['role'] == 2 && $user['role'] !== 1) {
                    $_SESSION['er_admin_id'] = $user['id'];
                    $_SESSION['er_admin_name'] = $user['name'];

                    $permissions = $db->select('SELECT `name` FROM `sections`')->fetchAll();
                    $this->setPageAccessSession($permissions);

                    $rand = md5(rand(0, 999999999));
                    $expiry = time() + (86400 * 30 * 6);
                    $db->update('admin_sis', $user['id'], ['remember_token', 'expire_remember_token'], [$rand, 2]);
                    setcookie("er_admin_co", $rand, $expiry);
                    $this->redirect('/');
                    exit();
                } else {
                    $this->flashMessage('error', 'کارمندی یافت نشد');
                    exit();
                }
            } else {
                $this->flashMessage('error', 'کارمندی یافت نشد');
                exit();
            }
        } else {
            $this->flashMessage('error', 'کارمندی یافت نشد');
            exit();
        }
    }


    // check user
    public function userCheck()
    {
        $db = new Database();
        if (isset($_COOKIE['er_admin_co'])) {
            $remember_token = $db->select('SELECT * FROM `admin_sis` WHERE remember_token = ?', [$_COOKIE['er_admin_co']])->fetch();
            if ($remember_token != null && $remember_token > 0 && $remember_token['expire_remember_token'] == 2) {
                $_SESSION['er_admin_id'] = $remember_token['id'];
                $_SESSION['er_admin_name'] = $remember_token['name'];

                $permissions = $db->select('SELECT `name` FROM `sections`')->fetchAll();
                $this->setPageAccessSession($permissions);
            }
        } else {
            $this->redirect('login');
            exit();
        }
    } //end check admin

    // check admin
    public function checkUser()
    {
        if (isset($_SESSION['user'])) {
            $db = new Database();
            $user = $db->select('SELECT * FROM al_users WHERE id = ?', [$_SESSION['user']])->fetch();
            if ($user != null) {
                if ($user['role'] != 1) {
                    $this->redirect('login');
                    exit();
                }
            } else {
                $this->redirect('login');
                exit();
            }
        } else {
            $this->redirect('login');
            exit();
        }
    }
}
