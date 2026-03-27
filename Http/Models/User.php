<?php

namespace Models\User;

use App\App;

class User extends App
{
    // get calendar type
    // public function handleAccountTransaction($data)
    // {
    //     $user = $this->db->select('SELECT * FROM users WHERE id = ?', [$data['user_id']])->fetch();
    //     if (!$user) {
    //         require_once BASE_PATH . '/404.php';
    //         exit();
    //     }

    //     $exist_customer_accounts = $this->db->select('SELECT * FROM account_balances WHERE user_id = ?', [$user['id']])->fetch();
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

    //         $this->db->update('account_balances', $exist_customer_accounts['id'], array_keys($update_account), $update_account);
    //     } else {
    //         $insert_account = [
    //             'user_id'   => $data['user_id'],
    //             'debtor' => $data['remaining'],
    //             'total_purchases' => $data['total_price'],
    //             'total_payments' => $data['paid_amount'],
    //             'total_discounts' => $data['sale_discount'],
    //         ];
    //         $this->db->insert('account_balances', array_keys($insert_account), $insert_account);
    //     }

    //     $users_transactions = [
    //         'ref_id'      => $data['ref_id'],
    //         'remaining'   => $data['remaining'],
    //         'seller_id'   => $data['user_id'],
    //         'total'       => $data['total_price'] - $data['sale_discount'],
    //         'paid_amount' => $data['paid_amount'],
    //         'transaction_date' => $data['invoice_date'],
    //         'year'        => $data['year'],
    //         'month'       => $data['month'],
    //         'who_it'      => $data['who_it'],
    //     ];
    //     $this->db->insert('users_transactions', array_keys($users_transactions), $users_transactions);

    // }

    // user transactions
    public function userTransaction($data)
    {
        $user = $this->db->select('SELECT * FROM users WHERE id = ?', [$data['user_id']])->fetch();
        if (!$user) {
            require_once BASE_PATH . '/404.php';
            exit();
        }

        $exist_customer_accounts = $this->db->select('SELECT * FROM account_balances WHERE user_id = ?', [$user['id']])->fetch();
        if ($exist_customer_accounts) {

            $debtor = $exist_customer_accounts['debtor'];
            $total_purchases = $exist_customer_accounts['total_purchases'];
            $total_payments = $exist_customer_accounts['total_payments'];
            $total_discounts = $exist_customer_accounts['total_discounts'];

            $update_account = [
                'debtor' => $data['remaining'] + $debtor,
                'total_purchases' => $data['total_price'] + $total_purchases,
                'total_payments' => $data['paid_amount'] + $total_payments,
                'total_discounts' => $data['sale_discount'] + $total_discounts,
            ];

            $this->db->update('account_balances', $exist_customer_accounts['id'], array_keys($update_account), $update_account);
        } else {
            $insert_account = [
                'user_id'   => $data['user_id'],
                'debtor' => $data['remaining'],
                'total_purchases' => $data['total_price'],
                'total_payments' => $data['paid_amount'],
                'total_discounts' => $data['sale_discount'],
            ];
            $this->db->insert('account_balances', array_keys($insert_account), $insert_account);
        }

        $users_transactions = [
            'ref_id'      => $data['ref_id'],
            'remaining'   => $data['remaining'],
            'seller_id'   => $data['user_id'],
            'total'       => $data['total_price'] - $data['sale_discount'],
            'paid_amount' => $data['paid_amount'],
            'date'        => $data['invoice_date'],
            'year'        => $data['year'],
            'month'       => $data['month'],
            'who_it'      => $data['who_it'],
        ];
        $this->db->insert('users_transactions', array_keys($users_transactions), $users_transactions);
    }
}
