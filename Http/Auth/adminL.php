<?php

namespace Auth;

require_once 'Http/Auth/Auth.php';

use Auth\Auth;
use database\Database;

class Login extends Auth
{
    // login page
    public function login()
    {
        if ((isset($_SESSION['sk_em_id']) && $_SESSION['sk_em_id'] != '') || isset($_SESSION['sk_em_name']) && $_SESSION['sk_em_name'] != '') {
            $this->redirect('/');
            exit();
        } else {
            require_once(BASE_PATH . '/resources/views/auth/login.php');
            exit();
        }
    }

    // check for login
    public function checkLogin($request)
    {
        if ($request['phone'] == '' || $request['password'] == '') {
            flash('error', 'لطفا تمام اطلاعات را وارد نمایید');
            $this->redirectBack();
            exit();
        }
        $db = new Database();
        $user = $db->select("SELECT * FROM `employees` WHERE phone = ? ", [$request['phone']])->fetch();

        if ($user != null && $user > 0) {
            if (password_verify($request['password'], $user['password'])) {
                if ($user['state'] == 1 && $user['state'] != 0 && $user['role'] == 1 && $user['role'] !== 2) {
                    $_SESSION['sk_em_id'] = $user['id'];
                    $rand = md5(rand(0, 999999999));
                    $expiry = time() + (86400 * 30 * 6);
                    $db->update('employees', $user['id'], ['remember_token', 'expire_remember_token'], [$rand, 2]);
                    setcookie("sk_em_co", $rand, $expiry);
                    $this->redirect('/');
                    exit();
                } elseif ($user['state'] == 1 && $user['state'] != 0 && $user['role'] == 2 && $user['role'] !== 1) {
                    $_SESSION['user_admin_id'] = $user['id'];
                    $rand = md5(rand(0, 999999999));
                    $expiry = time() + (86400 * 30 * 6);
                    $db->update('admin_sis', $user['id'], ['remember_token', 'expire_remember_token'], [$rand, 2]);
                    setcookie("user_admin", $rand, $expiry);
                    $this->redirect('/');
                    exit();
                } else {
                    flash('error', 'کارمندی یافت نشد');
                    $this->redirectBack();
                    exit();
                }
            } else {
                flash('error', 'کارمندی یافت نشد');
                $this->redirectBack();
                exit();
            }
        } else {
            flash('error', 'کارمندی یافت نشد');
            $this->redirectBack();
            exit();
        }
    }


    // check user
    public function userCheck()
    {
        $db = new Database();
        if (isset($_COOKIE['sk_em_co'])) {
            $remember_token = $db->select('SELECT * FROM `employees` WHERE remember_token = ?', [$_COOKIE['sk_em_co']])->fetch();
            if ($remember_token != null && $remember_token > 0 && $remember_token['expire_remember_token'] == 1) {
                $_SESSION['sk_em_id'] = $remember_token['id'];
                $_SESSION['sk_em_name'] = $remember_token['employee_name'];

                $permissions = $db->select('SELECT *, (SELECT `name` FROM `pages` WHERE pages.id = permissions.page_id) AS `pageName`  FROM permissions WHERE employee_id = ?', [$remember_token['id']])->fetchAll();
                $this->setPageAccessSession($permissions);
                exit();
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
