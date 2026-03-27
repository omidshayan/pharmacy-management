<?php

namespace Auth;

use database\Database;

class Auth
{
    // check admin
    public function checkUser()
    {
        if (isset($_SESSION['user_employee_id'])) {
            $db = new Database();
            $user = $db->select('SELECT * FROM employees WHERE id = ?', [$_SESSION['user_employee_id']])->fetch();
            if ($user != null) {
                if ($user['role'] != 1) {
                    $this->redirect('login');
                }
            } else {
                $this->redirect('login');
            }
        } else {
            $this->redirect('login');
        }
    }

    // check input user data
    function validation($data)
    {
        $user_input = array('<', '>', '/', '"', '\'', '(', ')', 'query', ',', ';', '[', ']', '$', 'SELEC', ':', '-', '=', '.', '#', '*');
        $data = str_replace($user_input, "", $data);
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    protected function redirect($url)
    {
        header('Location: ' . trim(CURRENT_DOMAIN, '/ ') . '/' . trim($url, '/ '));
        exit;
    }


    protected function redirectBack()
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function register()
    {
        if (isset($_SESSION['user'])) {
            $this->redirect('/');
        } else {
            require_once(BASE_PATH . '/resources/views/auth/register.php');
        }
    }

    // hash password
    public function hash($password)
    {
        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
        return $hashPassword;
    } //end hash password

    // make random token
    public function random()
    {
        return bin2hex(openssl_random_pseudo_bytes(32));
    } //end random token

    // set sessions pages employee
    function setPageAccessSession($permissions)
    {
        $pageNames = array_column($permissions, 'pageName');

        foreach ($pageNames as $pageName) {
            $_SESSION[$pageName] = $pageName;
        }
    }

    // set sessions pages admin
    function adminPageAccessSession($pagesNames)
    {
        $pageNames = array_column($pagesNames, 'pageName');

        foreach ($pageNames as $pageName) {
            $_SESSION[$pageName] = $pageName;
        }
    }

    // flash message
    function flashMessage($type, $message)
    {
        flash($type, $message);
        $this->redirectBack();
        exit();
    }
}
