<?php

namespace Auth;

require_once __DIR__ . '/Auth.php';

use Auth\Auth;
use database\Database;

class Login extends Auth
{
    // login page
    public function login()
    {
        if (
            (isset($_SESSION['phy_employee']) && !empty($_SESSION['phy_employee'])) ||
            (isset($_SESSION['phy_employee']) && !empty($_SESSION['phy_employee']))
        ) {
            $this->redirect('/');
            exit();
        } else {
            require_once(BASE_PATH . '/resources/views/auth/login.php');
            exit();
        }
    }


    public function checkLogin($request)
    {
        $phone = trim($request['phone']);
        $password = trim($request['password']);

        if ($phone == '' || $password == '') {
            $this->flashMessage('error', _emptyInputs);
        }

        $db = DataBase::getInstance();

        $user = $db->select("SELECT * FROM `employees` WHERE phone = ? ", [$phone])->fetch();

        // check user exist
        if ($user) {

            // check password
            if (password_verify($password, $user['password'])) {

                // check user employee
                if ($user['state'] == 1 && $user['role'] == 1 && $user['super_admin'] == null) {
                    $permissions = $db->select('SELECT `section_name` FROM `permissions` WHERE employee_id = ?', [$user['id']])->fetchAll();
                    $_SESSION['phy_employee'] = [
                        'id' => $user['id'],
                        'name' => $user['employee_name'],
                        'image' => $user['image'],
                        'role' => $user['role'],
                        'branch_id' => $user['branch_id'],
                        'permissions' => array_column($permissions, 'section_name')
                    ];

                    $rand = bin2hex(random_bytes(32));
                    $expiry = time() + (86400 * 30 * 6);
                    $db->update('employees', $user['id'], ['remember_token', 'expire_remember_token'], [$rand, 1]);
                    if (isset($request['remember_me']) && $request['remember_me'] == 'on') {
                        setcookie("phy_user", $rand, [
                            'expires' => $expiry,
                            'path' => '/',
                            'secure' => true,
                            'httponly' => true,
                            'samesite' => 'Strict'
                        ]);
                    }
                    $this->redirect('/');
                    exit();

                    // check super admin
                } elseif ($user['state'] == 1 && $user['role'] == 3 && $user['super_admin'] == 3) {
                    $permissions = $db->select('SELECT `en_name` FROM `sections`')->fetchAll();
                    $_SESSION['phy_employee'] = [
                        'id' => $user['id'],
                        'name' => $user['employee_name'],
                        'image' => $user['image'],
                        'role' => 3,
                        'admin' => 'admin',
                        'permissions' => array_column($permissions, 'en_name')
                    ];

                    $rand = bin2hex(random_bytes(32));
                    $expiry = time() + (86400 * 30 * 6);
                    $db->update('employees', $user['id'], ['remember_token', 'expire_remember_token'], [$rand, 3]);
                    if (isset($request['remember_me']) && $request['remember_me'] == 'on') {
                        setcookie("phy_user", $rand, [
                            'expires' => $expiry,
                            'path' => '/',
                            'secure' => true,
                            'httponly' => true,
                            'samesite' => 'Strict'
                        ]);
                    }
                    $this->redirect('/');
                    exit();
                } else {
                    $this->flashMessage('error', 'کاربری یافت نشد.');
                }
            } // end check password
            else {
                $this->flashMessage('error', 'کاربری یافت نشد.');
            }
        } // end check user exist
        else {
            $this->flashMessage('error', 'کاربری یافت نشد.');
        }
    }

    public function userCheck()
    {
        $db = DataBase::getInstance();
        if (isset($_COOKIE['phy_user'])) {

            $remember_token = $db->select('SELECT * FROM `employees` WHERE remember_token = ?', [$_COOKIE['phy_user']])->fetch();

            if (!$remember_token || !is_array($remember_token)) {
                $this->redirect('logout');
                exit();
            }

            if (!isset($_SESSION['settings']) || !is_array($_SESSION['settings'])) {
                $_SESSION['settings'] = $db->select('SELECT * FROM settings LIMIT 1')->fetch();
            }

            if ($remember_token['expire_remember_token'] == 1 && $remember_token['role'] == 1 && $remember_token['state'] == 1) {
                $permissions = $db->select('SELECT `section_name` FROM `permissions` WHERE employee_id = ?', [$remember_token['id']])->fetchAll();
                $_SESSION['phy_employee'] = [
                    'id' => $remember_token['id'],
                    'name' => $remember_token['employee_name'],
                    'image' => $remember_token['image'],
                    'role' => $remember_token['role'],
                    'branch_id' => $remember_token['branch_id'],
                    'permissions' => array_column($permissions, 'section_name')
                ];
            } elseif ($remember_token['state'] == 1 && $remember_token['role'] == 3 && $remember_token['super_admin'] == 3) {

                $permissions = $db->select('SELECT `en_name` FROM `sections`')->fetchAll();
                $_SESSION['phy_employee'] = [
                    'id' => $remember_token['id'],
                    'name' => $remember_token['employee_name'],
                    'image' => $remember_token['image'],
                    'role' => $remember_token['role'], // this is 3
                    'admin' => 'admin',
                    'permissions' => array_column($permissions, 'en_name')
                ];

                // $_SESSION['sk_permissions'] = array_column($permissions, 'en_name');
            }
        } else {
            $this->redirect('login');
            exit();
        }
    }
}



    // super admin login
    // public function superAdminLogin()
    // {
    //     if ((isset($_SESSION['so_super-admin_id']) && $_SESSION['so_super-admin_id'] != '') || isset($_SESSION['so_super-admin_name']) && $_SESSION['so-super-admin_name'] != '') {
    //         $this->redirect('/');
    //         exit();
    //     } else {
    //         require_once(BASE_PATH . '/resources/views/auth/super-admin-login.php');
    //         exit();
    //     }
    // }

    // super admin login checking
    // public function superLogin($request)
    // {
    //     $phone = trim($request['phone']);
    //     $password = trim($request['password']);

    //     if ($phone == '' || $password == '') {
    //         $this->flashMessage('error', _emptyInputs);
    //     }

    //     $db = DataBase::getInstance();

    //     $user = $db->select("SELECT * FROM `admin_sis` WHERE phone = ? ", [$phone])->fetch();

    //     if ($user) {
    //         if (password_verify($password, $user['password'])) {
    //             if ($user['state'] == 1 && $user['role'] == 3) {
    //                 $permissions = $db->select('SELECT `en_name` FROM `sections`')->fetchAll();
    //                 $_SESSION['super_admin'] = [
    //                     'id' => $user['id'],
    //                     'name' => $user['name'],
    //                     'image' => $user['image'],
    //                     'role' => $user['role'],
    //                     'permissions' => array_column($permissions, 'en_name')
    //                 ];

    //                 $rand = bin2hex(random_bytes(32));
    //                 $expiry = time() + (86400 * 30 * 6);
    //                 $db->update('admin_sis', $user['id'], ['remember_token', 'expire_remember_token'], [$rand, 3]);

    //                 if (isset($request['remember_me']) && $request['remember_me'] == 'on') {
    //                     setcookie("so_super_admin_co", $rand, [
    //                         'expires' => $expiry,
    //                         'path' => '/',
    //                         'secure' => true,
    //                         'httponly' => true,
    //                         'samesite' => 'Strict'
    //                     ]);
    //                 }
    //                 $this->redirect('/');
    //                 exit();
    //             } else {
    //                 $this->flashMessage('error', 'کاربری یافت نشد.');
    //             }
    //         } else {
    //             $this->flashMessage('error', 'کاربری یافت نشد.');
    //         }
    //     } else {
    //         $this->flashMessage('error', 'کاربری یافت نشد.');
    //     }
    // }
