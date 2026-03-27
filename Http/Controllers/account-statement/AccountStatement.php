<?php

namespace App;

require_once 'Http/Controllers/App.php';

class AccountStatement extends App
{
    // add expense page
    public function accountStatement()
    {
        $this->middleware(true, true, 'general', true);
        $branchId = $this->getBranchId();

        $users = $this->db->select(
            "SELECT 
                u.id, u.user_name, u.phone,
                IFNULL(ab.balance, 0) AS balance
             FROM users u
             LEFT JOIN account_balances ab ON u.id = ab.user_id 
             WHERE u.status = ? " . ($branchId !== 'ALL' ? "AND u.branch_id = ?" : "") . "
             ORDER BY u.id DESC",
            $branchId !== 'ALL' ? [1, $branchId] : [1]
        )->fetchAll();

        require_once(BASE_PATH . '/resources/views/app/account-statement/account-statement.php');
    }

    // details account statement
    public function userAccountStatement($id)
    {
        $this->middleware(true, true, 'general', true);
        
        $branchId = (int)$this->getBranchId();

        $user = $this->db->select('SELECT * FROM users WHERE branch_id = ? AND id = ?', [$branchId, $id])->fetch();
        if (!$user) die("کاربر یافت نشد.");

        $current_balance_row = $this->db->select('SELECT balance, total_out, total_in FROM account_balances WHERE user_id = ? AND branch_id = ?', [$id, $branchId])->fetch();
        $runningBalance = $current_balance_row ? (float)$current_balance_row['balance'] : 0.0;

        $user_statement = $this->db->select("
        SELECT *, 
        CASE 
            WHEN transaction_type IN (1, 2, 3, 4) THEN 'goods' 
            ELSE 'cash' 
            END AS source
            FROM users_transactions 
            WHERE user_id = ? AND branch_id = ? AND status = 1
            ORDER BY transaction_date DESC, id DESC
        ", [$user['id'], $branchId])->fetchAll();

        foreach ($user_statement as &$row) {
            $total = (float)$row['total_amount'];
            $paid = (float)$row['paid_amount'];
            $disc = (float)$row['discount'];
            $type = (int)$row['transaction_type'];

            $netAmount = $total - $disc - $paid;

            $row['running_balance'] = $runningBalance;


            switch ($type) {
                case 1:
                case 4:
                case 6:
                    $runningBalance += ($type == 6 ? $paid : $netAmount);
                    break;

                case 2:
                case 3:
                case 5:
                    $runningBalance -= ($type == 5 ? $paid : $netAmount);
                    break;
            }
        }

        $user_statement = array_reverse($user_statement);

        require_once(BASE_PATH . '/resources/views/app/account-statement/user-account-statement.php');
    }

    // get invoice details
    public function getInvoice($id)
    {
        $this->middleware(true, true, 'general', true);

        $branchId = $this->getBranchId();

        $transaction = $this->db->select('SELECT * FROM users_transactions WHERE id = ? AND branch_id = ?', [$id, $branchId])->fetch();

        if (!$transaction) {
            require_once(BASE_PATH . '/404.php');
            exit();
        }

        if (in_array($transaction['transaction_type'], [1, 2, 3, 4])) {

            $invoice = $this->db->select('SELECT * FROM invoices WHERE id = ? AND branch_id = ?', [$transaction['ref_id'], $branchId])->fetch();

            if (!$invoice) {
                require_once(BASE_PATH . '/404.php');
                exit();
            }

            $invoice_items = $this->db->select('SELECT * FROM invoice_items WHERE invoice_id = ?', [$invoice['id']])->fetchAll();
        }

        if (in_array($transaction['transaction_type'], [5, 6])) {

            $invoice = $this->db->select('SELECT * FROM cash_transactions WHERE id = ? AND branch_id = ?', [$transaction['ref_id'], $branchId])->fetch();
            if (!$invoice) {
                require_once(BASE_PATH . '/404.php');
                exit();
            }
        }

        require_once(BASE_PATH . '/resources/views/app/account-statement/invoice-details.php');
    }
}
