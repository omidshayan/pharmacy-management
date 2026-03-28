<?php

namespace App;

use database\DataBase;

trait Middleware
{
    protected $db;
    function __construct()
    {
        $this->db = DataBase::getInstance();
    }

    // middleware
    public function middleware($requireAuth = true, $requirePermission = true, $requiredPermission = null, $requireCSRF = false, &$request = null, $processRequest = false)
    {
        if ($requireAuth) {
            $this->isLoggedIn($request);
        }

        if ($requireCSRF && $request) {
            $this->validateCSRF($request, $this->db);
        }

        // check csrf token
        if ($requireCSRF) {
            $currentPage = $_SERVER['REQUEST_URI'];
            if (!isset($_SESSION['last_page']) || $_SESSION['last_page'] !== $currentPage) {
                $_SESSION['csrf_token'] = $this->generateCSRFToken();
                $_SESSION['last_page'] = $currentPage;
            }
        }

        // check permissions
        if ($requirePermission && $requiredPermission) {
            if (!$this->hasAccess($requiredPermission)) {
                $id = $_SESSION['phy_employee']['id'] ?? ($_SESSION['phy_employee']['id'] ?? null);

                if ($id) {
                    $user = $this->db->select('SELECT * FROM employees WHERE id = ?', [$id])->fetch();
                    if ($user) {

                        $infos = [
                            'user_id' => $user['id'],
                            'section_name' => $requiredPermission,
                            'page_address' => $_SERVER['REQUEST_URI'],
                            'ip_address' => $_SERVER['REMOTE_ADDR'],
                            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                        ];
                        $this->db->insert('not_access_logs', array_keys($infos), $infos);
                    }
                }

                require_once(BASE_PATH . '/notAccess.php');
                exit();
            }
        }

        if ($processRequest && $request) {
            $this->processRequest($request);
        }
    }

    // user isLoggedIn
    public function isLoggedIn()
    {
        $adminLoggedIn = isset($_SESSION['phy_employee']['id']) && isset($_SESSION['phy_employee']['name']);
        $employeeLoggedIn = isset($_SESSION['phy_employee']['id']) && isset($_SESSION['phy_employee']['name']);

        if (!$adminLoggedIn && !$employeeLoggedIn) {
            $this->redirect('login');
            exit();
        }
    }

    // validate csrf token
    public function validateCSRF($request, $db)
    {
        if (!isset($request['csrf_token']) || !isset($_SESSION['csrf_token']) || $request['csrf_token'] !== $_SESSION['csrf_token']) {
            $log_message = "Invalid or missing CSRF token. IP: {$_SERVER['REMOTE_ADDR']}, Time: " . date('Y-m-d H:i:s');
            $log_data = $this->parseLogMessage($log_message);
            $db->insert('csrf_token_logs', array_keys($log_data), array_values($log_data));
            http_response_code(403);
            $this->flashMessage('error', 'درخواست شما معتبر نمی باشد (403)');
            exit();
        }
    }

    // Generate a random token
    function generateCSRFToken()
    {
        return bin2hex(random_bytes(32));
    }

    // check users permissions
    function hasAccess($page)
    {
        if (isset($_SESSION['phy_employee']['permissions']) && is_array($_SESSION['phy_employee']['permissions'])) {
            if (in_array($page, $_SESSION['phy_employee']['permissions'])) {
                return true;
            }
        }

        if (isset($_SESSION['phy_employee']['permissions']) && is_array($_SESSION['phy_employee']['permissions'])) {
            if (in_array($page, $_SESSION['phy_employee']['permissions'])) {
                return true;
            }
        }

        return false;
    }

    // unset csrf token and add who_it
    private function processRequest(&$request)
    {
        unset($request['csrf_token']);

        if (isset($_SESSION['phy_employee']['name'])) {
            $request['who_it'] = $_SESSION['phy_employee']['name'];
        } elseif (isset($_SESSION['phy_employee']['name'])) {
            $request['who_it'] = $_SESSION['phy_employee']['name'];
        } else {
            require_once(BASE_PATH . '/notAccess.php');
            exit();
        }
    }

    // csrf token
    private function parseLogMessage($message)
    {
        preg_match('/^(.*) IP: (.*?), Time: .*$/', $message, $matches);
        return [
            'message' => $matches[1] ?? 'unknown',
            'ip_address' => $matches[2] ?? 'unknown',
        ];
    }

    // redirect to ...
    function flashMessageTo($type, $message, $redirectTo)
    {
        flash($type, $message);

        if ($type === 'success') {
            unset($_SESSION['old']);
            unset($_SESSION['temporary_old']);
        }

        header('Location: ' . $redirectTo);
        exit;
    }

    // flash message
    function flashMessage($type, $message)
    {
        flash($type, $message);
        if ($type === 'success') {
            unset($_SESSION['old']);
            unset($_SESSION['temporary_old']);
        }
        $this->redirectBack();
        exit();
    }
    // print id
    function flashMessageId($type, $message, $id = null)
    {
        flash($type, $message);

        if ($id !== null) {
            $_SESSION['flash_id'] = $id;
        }

        if ($type === 'success') {
            unset($_SESSION['old']);
            unset($_SESSION['temporary_old']);
        }

        $this->redirectBack();
        exit();
    }

    // flash message to print
    function flashMessagePrint($type, $message, $extra = [])
    {
        flash($type, $message);

        if (!empty($extra)) {
            $_SESSION['print_data'] = $extra;
        }

        if ($type === 'success') {
            unset($_SESSION['old']);
            unset($_SESSION['temporary_old']);
        }

        $this->redirectBack();
        exit();
    }

    // redirect back
    protected function redirectBack()
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
