<?php

namespace App;

require_once 'Http/Controllers/App.php';
require_once 'Http/Models/Invoice.php';

use Models\Invoice\Invoice;
use PDO;

class User extends App
{
    private $invoice;
    public function __construct()
    {
        parent::__construct();
        $this->invoice = new Invoice();
    }

    // add User page
    public function addUser()
    {
        $this->middleware(true, true, 'general', true);
        require_once(BASE_PATH . '/resources/views/app/users/add-user.php');
        exit();
    }

    // store user
    public function userStore($request)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        if (
            empty($request['user_name']) ||
            empty($request['phone']) ||
            empty($request['branch_id'])
        ) {
            $this->flashMessage('error', _emptyInputs);
            return;
        }

        $existingEmployee = $this->db->select('SELECT * FROM users WHERE `phone` = ?', [$request['phone']])->fetch();
        if ($existingEmployee) {
            $this->flashMessage('error', _phone_repeat);
            return;
        }

        if ($request['password'] == '') {
            $request['password'] = $request['phone'];
        }
        $request['password'] = $this->hash($request['password']);

        $this->validateInputs($request, ['user_image' => false]);

        $is_customer = isset($request['is_customer']) ? 1 : null;
        $is_seller = isset($request['is_seller']) ? 1 : null;
        $request['is_customer'] = $is_customer;
        $request['is_seller'] = $is_seller;

        // check image
        $request['user_image'] = $this->handleImageUpload($request['user_image'], 'images/users');

        $note = !empty($request['note']) ? $request['note'] : 'باقیداری اولیه هنگام ثبت کاربر';
        unset($request['note']);

        try {
            $this->db->beginTransaction();

            $this->db->insert('users', array_keys($request), $request);

            $newUserId = $this->db->lastInsertId();

            if (!$newUserId) {
                $row = $this->db->select('SELECT id FROM users WHERE phone = ?', [$request['phone']])->fetch();
                $newUserId = $row ? $row->id : null;
            }

            if (!$newUserId) {
                throw new \Exception('Cannot get new user id after insert.');
            }

            $remnantsPast = isset($request['remnants_past']) && $request['remnants_past'] !== ''
                ? (float)$request['remnants_past']
                : 0;

            $creditPast = isset($request['credit_past']) && $request['credit_past'] !== ''
                ? (float)$request['credit_past']
                : 0;


            if ($remnantsPast > 0 && $creditPast > 0) {
                throw new \Exception('کاربر نمی‌تواند همزمان قرضدار و طلبکار باشد');
            }

            $now = time();

            $balance = 0;
            $type    = null;
            $amount  = 0;

            if ($remnantsPast > 0) {
                $balance = -abs($remnantsPast);
                $type    = 7;
                $amount  = $remnantsPast;
            } elseif ($creditPast > 0) {
                $balance = abs($creditPast);
                $type    = 8;
                $amount  = $creditPast;
            }

            $accountBalance = [
                'branch_id' => (int)$request['branch_id'],
                'user_id'   => (int)$newUserId,
                'balance'   => $balance,
            ];

            $this->db->insert('account_balances', array_keys($accountBalance), $accountBalance);
            if ($amount > 0) {
                $data = [
                    'branch_id'   => (int)$request['branch_id'],
                    'user_id'     => (int)$newUserId,
                    'amount'      => $amount,
                    'type'        => $type,
                    'date'        => $now,
                    'description'   => $note,
                    'who_it'      => $request['who_it'] ?? 'system',
                    'currency'    => $request['currency'] ?? 'af',
                ];

                $this->db->insert('cash_transactions', array_keys($data), $data);
            }


            $this->db->commit();

            $this->flashMessage('success', _success);
        } catch (\Exception $e) {
            $this->db->rollBack();
            $this->flashMessage('error', 'خطا در ثبت کاربر: ' . $e->getMessage());
        }
    }

    // show users
    public function showUsers()
    {
        $this->middleware(true, true, 'general');
        $users = $this->db->select('SELECT * FROM users WHERE `status` != 0')->fetchAll();
        require_once(BASE_PATH . '/resources/views/app/users/show-users.php');
        exit();
    }

    // edit user page
    public function editUser($id)
    {
        $this->middleware(true, true, 'general', true);

        $user = $this->db->select('SELECT * FROM users WHERE id = ?', [$id])->fetch();

        if ($user != null) {
            require_once(BASE_PATH . '/resources/views/app/users/edit-user.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // edit user store
    public function editUserStore($request, $id)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        if (empty($request['user_name']) || empty($request['phone'])) {
            $this->flashMessage('error', _emptyInputs);
            return;
        }

        if ($id == 1) {
            $this->flashMessage('error', 'اطلاعات کاربر عمومی قابل ویرایش نیست');
            return;
        }

        $this->db->beginTransaction();

        try {
            $user = $this->db->select('SELECT id, phone FROM users WHERE id = ? FOR UPDATE', [$id])->fetch();

            if (!$user) {
                $this->db->rollBack();
                require_once(BASE_PATH . '/404.php');
                exit();
            }

            $existing_user = $this->db->select('SELECT id FROM users WHERE phone = ? FOR UPDATE', [$request['phone']])->fetch();

            if ($existing_user && $existing_user['id'] != $id) {
                $this->db->rollBack();
                $this->flashMessage('error', 'این شماره موبایل قبلاً توسط کاربر دیگری ثبت شده است.');
                return;
            }

            if ($request['password'] = trim($request['password'])) $request['password'] = $this->hash($request['password']);
            else unset($request['password']);

            $this->updateImageUpload($request, 'user_image', 'users', 'users', $id);

            $this->db->update('users', $id, array_keys($request), $request);

            $this->db->commit();

            $this->flashMessageTo('success', 'اطلاعات کاربر با موفقیت ویرایش شد.', url('users'));
        } catch (Exception $e) {
            $this->db->rollBack();
            $this->flashMessage('error', 'خطایی در هنگام ویرایش رخ داد. لطفاً دوباره تلاش کنید.');
        }
    }

    // user detiles page
    public function userDetails($id)
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();

        $user = $this->db->select('SELECT * FROM users WHERE id = ? AND branch_id = ?', [$id, $branchId])->fetch();

        if ($user != null) {

            $account_balance = $this->db->select('SELECT * FROM account_balances WHERE user_id = ?  AND branch_id = ?', [$id, $branchId])->fetch();

            require_once(BASE_PATH . '/resources/views/app/users/user-details.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // change status employee
    public function changeStatus($id)
    {
        $this->middleware(true, true, 'students');
        $department = $this->db->select('SELECT * FROM departments WHERE id = ?', [$id])->fetch();
        if ($department != null) {
            if ($department['state'] == 1) {
                $this->db->update('departments', $department['id'], ['state'], [2]);
                flash('success', _success);
                $this->redirectBack();
                exit();
            } else {
                $this->db->update('departments', $department['id'], ['state'], [1]);
                flash('success', _success);
                $this->redirectBack();
                exit();
            }
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // user search details
    public function searchUserDetails($request)
    {
        $this->middleware(true, true, 'general', true);

        $usre = $this->db->select("SELECT * FROM users WHERE user_name LIKE ?", ['%' . $request['customer_name'] . '%'])->fetchAll();

        $response = [
            'status' => 'success',
            'items' => $usre,
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    // search item
    public function searchItem($request)
    {
        $this->middleware(true, true, 'general');
        $infos = $this->db->select("SELECT * FROM users WHERE `user_name` LIKE ?", ['%' . $request['customer_name'] . '%'])->fetchAll();

        $response = [
            'status' => 'success',
            'items' => $infos,
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
}
