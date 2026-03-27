<?php

namespace App;

require_once 'Http/Models/Calendar.php';
require_once 'Http/Models/Transaction.php';
require_once 'Http/Models/Notification.php';
require_once 'Http/Models/Reports.php';

use Models\Calendar\Calendar;
use Models\Transaction\Transaction;
use Models\Notification\Notification;
use Models\Reports\Reports;

class FinancialSector extends App
{
    private $calendar;
    private $transaction;
    private $notification;
    private $reports;
    public function __construct()
    {
        $this->calendar = new Calendar();
        $this->transaction = new Transaction();
        $this->notification = new Notification();
        $this->reports = new Reports();
        parent::__construct();
    }

    // deposit money page
    public function depositMoney()
    {
        $this->middleware(true, true, 'general', true);

        $users = $this->db->select('SELECT * FROM users WHERE `status` = ?', [1])->fetchAll();

        if (!$users) {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
        $branchId = $this->getBranchId();

        $cash_boxes = $this->db->select(
            'SELECT id, `name` FROM cash_boxes WHERE `status` = ? AND branch_id = ?',
            [1, $branchId]
        )->fetchAll();

        require_once(BASE_PATH . '/resources/views/app/financial-sectors/deposit-money.php');
        exit();
    }

    // deposit Money Store
    public function depositMoneyStore($request)
    {
        $this->middleware(true, true, 'general');

        if (empty($request['user_id']) || empty($request['amount']) || empty($request['type']) || empty($request['source'])) {
            $this->flashMessage('error', 'لطفا اطلاعات ضروری را وارد نمایید!');
            return;
        }

        $userInfo = $this->currentUser();
        $branchId = $userInfo['branch_id'];

        $cashBoxId = (int)$request['source'];
        $transaction_type = (int)$request['type']; // 5 برای رسید (دریافت)، 6 برای پرداخت
        $price = intval($request['amount']);

        $user = $this->db->select('SELECT * FROM users WHERE id = ? AND branch_id = ?', [$request['user_id'], $branchId])->fetch();
        if (!$user) {
            require_once(BASE_PATH . '/404.php');
            exit();
        }

        $customerId = $request['user_id'];

        $this->db->beginTransaction();

        try {
            $previousBalance = $this->getCustomerBalance($user['id'], $branchId);

            $fromCash = ($transaction_type == 6) ? $cashBoxId : null;
            $toCash   = ($transaction_type == 5) ? $cashBoxId : null;

            $this->reports->updateFund([
                'branch_id'        => $branchId,
                'user_id'          => $customerId,
                'from_cash_id'     => $fromCash,
                'to_cash_id'       => $toCash,
                'amount'           => $price,
                'type'             => $transaction_type,
                'date'             => $request['transaction_date'],
                'description'      => !empty($request['description']) ? $request['description'] : null,
                'who_it'           => $userInfo['name'],
                'currency'         => $request['currency'] ?? 'af',
                'source'         => $request['source'],
            ]);
            
            $cashTransactionId = $this->db->lastInsertId();

            $this->transaction->addNewTransaction([
                'branch_id'        => $branchId,
                'user_id'          => $customerId,
                'ref_id'           => $cashTransactionId,
                'transaction_type' => $transaction_type,
                'total_amount'     => $price,
                'paid_amount'      => $price,
                'transaction_date' => $request['transaction_date'],
                'description'      => $request['description'] ?? null,
                'status'           => 1,
                'who_it'           => $userInfo['name'],
            ]);

            $this->notification->sendNotif([
                'branch_id' => $branchId,
                'user_id'   => $user['id'],
                'ref_id'    => $cashTransactionId,
                'type'      => $transaction_type,
            ]);

            $this->db->commit();

            if (isset($request['invoice_print'])) {
                $this->flashMessageId('success', 'عملیات با موفقیت ثبت شد', $cashTransactionId);
            } else {
                $this->flashMessage('success', 'عملیات با موفقیت ثبت شد');
            }
        } catch (Exception $e) {
            $this->db->rollback();
            $this->flashMessage('error', 'خطا در ثبت عملیات: ' . $e->getMessage());
        }
    }

    // show all deposit money
    public function financialSector()
    {
        $this->middleware(true, true, 'general', true);

        $branchId = $this->getBranchId();

        $cash = $this->db->select(
            "SELECT 
            ct.*, 
            u.user_name AS user_name,
            cb.name AS box_name
         FROM cash_transactions ct
         LEFT JOIN users u ON ct.user_id = u.id
         LEFT JOIN cash_boxes cb ON (ct.from_cash_id = cb.id OR ct.to_cash_id = cb.id)
         WHERE ct.branch_id = ? 
         AND ct.type IN (5, 6) 
         ORDER BY ct.id DESC",
            [$branchId]
        )->fetchAll();

        require_once(BASE_PATH . '/resources/views/app/financial-sectors/financial-sectors.php');
        exit();
    }

    // financial sector details
    public function financialSectorDetails($id)
    {
        $this->middleware(true, true, 'general', true);

        $transaction = $this->db->select('
            SELECT ct.*, u.user_name
            FROM cash_transactions AS ct
            LEFT JOIN users AS u ON u.id = ct.user_id
            WHERE ct.transaction_number = ?
        ', [$id])->fetch();

        if (!$transaction) {
            require_once(BASE_PATH . '/404.php');
            exit();
        }

        $notif = $this->db->select('SELECT * FROM notifications WHERE ref_id = ?', [$transaction['transaction_number']])->fetch();

        $date = date('Y/m/d');
        $this->db->update('notifications', $notif['id'], ['state', 'read_at'], [2, $date]);

        require_once(BASE_PATH . '/resources/views/app/financial-sectors/financial-sector-details.php');
        exit();
    }

    ///////////////// temp //////////////////
    // user search
    public function userSearch()
    {
        $this->middleware(true, true, 'general', true);
        require_once(BASE_PATH . '/resources/views/app/financial-sectors/user-search.php');
        exit();
    }

    // deposit money page
    public function depositMoneyT($id)
    {
        $this->middleware(true, true, 'general', true);

        $user = $this->db->select('SELECT * FROM users WHERE id = ?', [$id])->fetch();

        if (!$user) {
            require_once(BASE_PATH . '/404.php');
            exit();
        }

        $balance = $this->db->select('SELECT balance FROM account_balances WHERE user_id = ?', [$user['id']])->fetch();

        require_once(BASE_PATH . '/resources/views/app/financial-sectors/deposit-money.php');
        exit();
    }

    // result user search
    public function searchUser($request)
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
}
