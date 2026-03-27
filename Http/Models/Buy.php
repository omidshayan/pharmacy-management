<?php

namespace Models\Buy;

use App\App;

class Buy extends App
{
    // add new buy transaction and update user account in database
    public function addNewTransaction($data)
    {
        $user = $this->db->select('SELECT * FROM users WHERE id = ?', [$data['user_id']])->fetch();
        if (!$user) {
            throw new \Exception('کاربر یافت نشد');
        }

        // for account balances
        $exist_accounts = $this->db->select('SELECT * FROM account_balances WHERE user_id = ? AND branch_id = ?', [$user['id'], $data['branch_id']])->fetch();
        
        $update_account = [];
        if ($exist_accounts) {
            $update_account = [
                'branch_id' => $data['branch_id'],
                'user_id' => $user['id'],
                'total_purchase_amount' => $exist_accounts['total_purchase_amount'] ?? 0,
                'total_paid_to_user' => $exist_accounts['total_paid_to_user'] ?? 0,
                'total_purchase_discount' => $exist_accounts['total_purchase_discount'] ?? 0,
                'creditor' => $exist_accounts['creditor'] ?? 0,
                'total_sale_amount' => $exist_accounts['total_sale_amount'] ?? 0,
                'total_received_from_user' => $exist_accounts['total_received_from_user'] ?? 0,
                'total_sale_discount' => $exist_accounts['total_sale_discount'] ?? 0,
                'debtor' => $exist_accounts['debtor'] ?? 0,
                'total_purchase_return' => $exist_accounts['total_purchase_return'] ?? 0,
                'total_received_buy_return' => $exist_accounts['total_received_buy_return'] ?? 0,
            ];
        } else {
            $update_account = [
                'branch_id' => $data['branch_id'],
                'user_id' => $user['id'],
                'total_purchase_amount' => 0,
                'total_paid_to_user' => 0,
                'total_purchase_discount' => 0,
                'creditor' => 0,
                'total_sale_amount' => 0,
                'total_received_from_user' => 0,
                'total_sale_discount' => 0,
                'debtor' => 0,
                'total_purchase_return' => 0,
                'total_received_buy_return' => 0,
            ];
        } // end account balances

        switch ($data['transaction_type']) {
            case 2: // buy transaction
                $update_account['total_purchase_amount'] += $data['total_price'];
                $update_account['total_paid_to_user'] += $data['paid_amount'];
                $update_account['total_purchase_discount'] += $data['discount'] ?? 0;
                $update_account['creditor'] += $data['creditor'] ?? 0;
                $update_account['year'] = $data['year'];
                break;

            case 4: // return from buy transaction
                $update_account['total_purchase_return'] += $data['total_price'];
                $update_account['total_received_buy_return'] += $data['paid_amount'];
                $update_account['debtor'] += $data['debtor'];
                $update_account['year'] = $data['year'];
                break;





                
            case 1:
                $update_account['total_sale_amount'] += $data['total_price'];
                $update_account['total_received_from_user'] += $data['paid_amount'];
                $update_account['total_sale_discount'] += $data['discount'] ?? 0;
                $update_account['debtor'] += $data['debtor'] ?? 0;
                break;
            case 3:
                $update_account['total_sale_amount'] -= $data['total_price'];
                $update_account['total_received_from_user'] -= $data['paid_amount'];
                $update_account['total_sale_discount'] -= $data['discount'] ?? 0;
                $update_account['debtor'] -= $data['debtor'] ?? 0;
                break;
            default:
                throw new \Exception('نوع تراکنش نامعتبر است');
        }

        if ($exist_accounts) {
            $updated = $this->db->update('account_balances', $exist_accounts['id'], array_keys($update_account), $update_account);
            if (!$updated) {
                throw new \Exception('خطا در بروزرسانی حساب کاربر');
            }
        } else {
            $update_account['user_id'] = $user['id'];
            $inserted = $this->db->insert('account_balances', array_keys($update_account), $update_account);
            if (!$inserted) {
                throw new \Exception('خطا در افزودن حساب جدید به کاربر');
            }
        }

        $user_transactions = [
            'branch_id' => $data['branch_id'],
            'user_id' => $user['id'],
            'ref_id' => $data['ref_id'],
            'total_price' => $data['total_price'],
            'paid_amount' => $data['paid_amount'],
            'transaction_date' => $data['date'],
            'year' => $data['year'],
            'month' => $data['month'],
            'who_it' => $data['who_it'],
            'transaction_type' => $data['transaction_type'],
        ];

        if (isset($data['discount']) && $data['discount'] !== '') {
            $user_transactions['discount'] = $data['discount'];
        }

        $insertedTransaction = $this->db->insert('users_transactions', array_keys($user_transactions), $user_transactions);
        if (!$insertedTransaction) {
            throw new \Exception('خطا در ثبت تراکنش');
        }
    }

    // send notifications
    public function sendNotif($notifData)
    {
        $admins = $this->db->select('SELECT id FROM employees WHERE role = ?', [2])->fetchAll();

        $notifs = [];

        switch ($notifData['type']) {
            case 'buy':
                $adminMsg =  'بِل خرید (شماره ' . $notifData['ref_id'] . ') ثبت شد.';
                $userMsg = 'بِل فروش (شماره ' . $notifData['ref_id'] . ') برای شما ثبت شد.';
                break;
            case 'return_from_buy':
                $adminMsg =  'بِل برگشت از خرید (شماره ' . $notifData['ref_id'] . ') ثبت شد.';
                $userMsg = 'بِل برگشت از فروش (شماره ' . $notifData['ref_id'] . ') برای شما ثبت شد.';
                break;
            case 'return_from_sale':
                $adminMsg =  'بِل برگشت از فروش (شماره ' . $notifData['ref_id'] . ') ثبت شد.';
                $userMsg = 'بِل برگشت از خرید (شماره ' . $notifData['ref_id'] . ') برای شما ثبت شد.';
                break;
            case 'sale':
                $adminMsg =  'بِل فروش (شماره ' . $notifData['ref_id'] . ') ثبت شد.';
                $userMsg = 'بِل خرید (شماره ' . $notifData['ref_id'] . ') برای شما ثبت شد.';
                break;
            default:
                $adminMsg = 'تراکنش جدید ثبت شد.';
                $userMsg = 'تراکنش جدید برای شما ثبت شد.';
        }

        foreach ($admins as $admin) {
            $notifs[] = [
                'user_id' => $admin['id'],
                'ref_id' => $notifData['ref_id'],
                'msg' => $adminMsg,
            ];
        }

        if (!empty($notifData['seller_id']) && $notifData['seller_id'] > 0) {
            $notifs[] = [
                'user_id' => $notifData['seller_id'],
                'ref_id' => $notifData['ref_id'],
                'msg' => $userMsg,
            ];
        }

        foreach ($notifs as $notif) {
            $this->db->insert('notifications', array_keys($notif), $notif);
        }
    }

    // update main fund
    // public function updateFinancialSummary($financialData)
    // {   
    //     $row = $this->db->select('SELECT * FROM financial_summary WHERE branch_id = ?', [$financialData['branch_id']])->fetch();
    //     $update = [];

    //     switch ($financialData['type']) {
    //         case 'buy':
    //             $update['branch_id'] = $financialData['branch_id'];
    //             $update['total_purchases_count'] = $row['total_purchases_count'] + 1;
    //             $update['total_cash_out'] = $row['total_cash_out'] + $financialData['paid_amount'];
    //             $update['total_debt_to_users'] = $row['total_debt_to_users'] + $financialData['total_debt_to_users'];
    //             $update['total_purchase_amount'] = $row['total_purchase_amount'] + $financialData['total_purchase_amount'];
    //             $update['total_purchase_discount'] = $row['total_purchase_discount'] + $financialData['total_purchase_discount'];
    //             $update['current_balance'] = $row['current_balance'] - $financialData['paid_amount'];
    //             break;

    //         case 'return_buy':
    //             $update['total_return_from_purchase'] = $row['total_return_from_purchase'] + $financialData['total_return_from_purchase'];
    //             $update['current_balance'] = $row['current_balance'] + $financialData['current_balance'];
    //             $update['total_cash_in'] = $row['total_cash_in'] + $financialData['total_cash_in'];
    //             $update['total_debt_to_users'] = $row['total_debt_to_users'] - $financialData['total_debt_to_users'];
    //             break;




    //         case 'sell':
    //             $update['total_sales_count'] = $row['total_sales_count'] + 1;
    //             $update['total_cash_in'] = $row['total_cash_in'] + $financialData['received_amount'];
    //             $update['total_debt_from_customers'] = $row['total_debt_from_customers'] + $financialData['customer_debt'];
    //             $update['total_sales_amount'] = $row['total_sales_amount'] + $financialData['total_sales_amount'];
    //             $update['total_discount'] = $row['total_discount'] + $financialData['discount'];
    //             $update['total_profit'] = $row['total_profit'] + $financialData['profit'];
    //             $update['current_balance'] = $row['current_balance'] + $financialData['received_amount'];
    //             break;

    //         case 'sell_return':
    //             $update['total_sell_returns_count'] = $row['total_sell_returns_count'] + 1;
    //             $update['total_cash_out'] = $row['total_cash_out'] + $financialData['returned_cash'];
    //             $update['total_sales_return_amount'] = $row['total_sales_return_amount'] + $financialData['return_amount'];
    //             $update['total_profit'] = $row['total_profit'] - $financialData['profit_reduction'];
    //             $update['current_balance'] = $row['current_balance'] - $financialData['returned_cash'];
    //             break;

    //         case 'expense':
    //             $update['total_expense'] = $row['total_expense'] + $financialData['amount'];
    //             $update['total_cash_out'] = $row['total_cash_out'] + $financialData['amount'];
    //             $update['current_balance'] = $row['current_balance'] - $financialData['amount'];
    //             break;

    //         case 'income':
    //             $update['total_income'] = $row['total_income'] + $financialData['amount'];
    //             $update['total_cash_in'] = $row['total_cash_in'] + $financialData['amount'];
    //             $update['current_balance'] = $row['current_balance'] + $financialData['amount'];
    //             break;

    //         default:
    //             return;
    //     }

    //     $this->db->update('financial_summary', $row['id'], array_keys($update), array_values($update));
    // }

    // cash drawer
    public function cashDreawer($data){
         $cash_drawer = $this->db->select('SELECT * FROM cash_drawer WHERE branch_id = ?', [$data['branch_id']])->fetch();
         $nrew_balance = $data['paid_amount'] + $cash_drawer['balance'];
    }
}
