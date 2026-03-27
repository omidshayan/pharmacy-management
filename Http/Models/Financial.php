<?php

namespace Models\Financial;

use App\App;

class Financial extends App
{
    public function daily_reports($data)
    {
        $required_keys = ['total_sales', 'total_payments', 'total_discounts', 'total_remaining'];
        foreach ($required_keys as $key) {
            if (!isset($data[$key]) || !is_numeric($data[$key])) {
                $this->flashMessage('error', 'لطفا مقادیر عددی وارد نمائید!');
            }
        }

        $today = date('Y-m-d');
        $daily_report = $this->db->select(
            'SELECT * FROM daily_reports WHERE DATE(created_at) = ? ORDER BY created_at DESC LIMIT 1',
            [$today]
        )->fetch();

        if ($daily_report) {
            $reports = [
                'total_sales'     => $daily_report['total_sales'] + $data['total_sales'],
                'total_payments'  => $daily_report['total_payments'] + $data['total_payments'],
                'total_discounts' => $daily_report['total_discounts'] + $data['total_discounts'],
                'total_remaining' => $daily_report['total_remaining'] + $data['total_remaining'],
            ];
            $this->db->update('daily_reports', $daily_report['id'], array_keys($reports), $reports);
        } else {
            $new_reports = [
                'total_sales'     => $data['total_sales'],
                'total_payments'  => $data['total_payments'],
                'total_discounts' => $data['total_discounts'],
                'total_remaining' => $data['total_remaining'],
            ];
            $this->db->insert('daily_reports', array_keys($new_reports), $new_reports);
        }
    }

    // user handle balances
    public function userBalances($data)
    {
        $user = $this->db->select('SELECT * FROM users WHERE id = ?', [$data['user_id']])->fetch();
        if (!$user) {
            throw new \Exception('کاربر یافت نشد');
        }

        $exist_accounts = $this->db->select('SELECT * FROM account_balances WHERE user_id = ?', [$user['id']])->fetch();
        if ($exist_accounts) {
            $debtor = $exist_accounts['debtor'];
            $total_purchase_return = $exist_accounts['total_purchase_return'];
            $received_from_user = $exist_accounts['total_received_from_user'] + $data['paid_amount'];

            $update_account = [
                'debtor' => $data['debtor'] + $debtor,
                'total_received_from_user' => $received_from_user,
                'total_purchase_return' => $data['this_total_price'] + $total_purchase_return,
            ];

            $updated = $this->db->update('account_balances', $exist_accounts['id'], array_keys($update_account), $update_account);
            if (!$updated) {
                throw new \Exception('خطا در بروزرسانی حساب کاربر');
            }
        } else {
            $debtor = 0;
            $total_purchase_return = 0;
            $received_from_user = $data['paid_amount'];
            $add_account = [
                'user_id'   => $user['id'],
                'debtor' => $data['debtor'] + $debtor,
                'total_received_from_user' => $received_from_user,
                'total_purchase_return' => $data['this_total_price'] + $total_purchase_return,
            ];
            $inserted = $this->db->insert('account_balances', array_keys($add_account), $add_account);
            if (!$inserted) {
                throw new \Exception('خطا در افزودن حساب جدید به کاربر');
            }
        }
    }

        // get calendar type
    // public function handleAccountTransaction($data)
    // {
    //     $user = $this->db->select('SELECT * FROM users WHERE id = ?', [$data['user_id']])->fetch();
    //     if (!$user) {
    //         require_once BASE_PATH . '/404.php';
    //         exit();
    //     }

    //     $exist_customer_accounts = $this->db->select('SELECT * FROM customer_accounts WHERE user_id = ?', [$user['id']])->fetch();
    //     if ($exist_customer_accounts) {

    //         $debtor = $exist_customer_accounts['debtor'];
    //         $total_purchases = $exist_customer_accounts['total_purchases'];
    //         $total_payments = $exist_customer_accounts['total_payments'];
    //         $total_discounts = $exist_customer_accounts['total_discounts'];

    //         $update_account = [
    //             'debtor' => $data['remaining'] + $debtor,
    //             'total_purchases' => $data['total_price'] + $total_purchases,
    //             'total_payments' => $data['paid_amount'] + $total_payments,
    //             'total_discounts' => $data['sale_discount'] + $total_discounts,
    //         ];

    //         $this->db->update('customer_accounts', $exist_customer_accounts['id'], array_keys($update_account), $update_account);
    //     } else {
    //         $insert_account = [
    //             'user_id'   => $data['user_id'],
    //             'debtor' => $data['remaining'],
    //             'total_purchases' => $data['total_price'],
    //             'total_payments' => $data['paid_amount'],
    //             'total_discounts' => $data['sale_discount'],
    //         ];
    //         $this->db->insert('customer_accounts', array_keys($insert_account), $insert_account);
    //     }

    //     if ($data['paid_amount'] > 0) {
    //         $users_transactions = [
    //             'seller_id'   => $data['user_id'],
    //             'paid_amount' => $data['paid_amount'],
    //             'date'        => $data['invoice_date'],
    //             'year'        => $data['year'],
    //             'month'       => $data['month'],
    //             'who_it'      => $data['who_it'],
    //         ];
    //         $this->db->insert('users_transactions', array_keys($users_transactions), $users_transactions);
    //     }
    // }
}
