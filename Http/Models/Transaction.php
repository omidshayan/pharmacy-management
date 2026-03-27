<?php

namespace Models\Transaction;

use App\App;

class Transaction extends App
{
    // add new transactions
    public function addNewTransaction($data)
    {
        $user = $this->db->select('SELECT * FROM users WHERE id = ?', [$data['user_id']])->fetch();
        $account = $this->db->select('SELECT id, balance, total_out, total_in FROM account_balances WHERE branch_id = ? AND user_id = ?', [$data['branch_id'], $data['user_id']])->fetch();

        if (!$user || !$account) {
            throw new \Exception('کاربر یا حساب مالی مورد نظر یافت نشد');
        }

        $total    = isset($data['total_amount']) ? (float)$data['total_amount'] : 0;
        $discount = isset($data['discount']) ? (float)$data['discount'] : 0;
        $paid     = isset($data['paid_amount']) ? (float)$data['paid_amount'] : 0;

        $netInvoice = $total - $discount; // مبلغ خالص فاکتور بدون در نظر گرفتن پرداخت
        $type       = (int)$data['transaction_type'];

        $newTotalIn  = (float)$account['total_in'];
        $newTotalOut = (float)$account['total_out'];
        $newBalance  = (float)$account['balance'];

        switch ($type) {
            case 1: // sale to customer
                // مشتری کالا برده (مانده منفی می‌شود) و پول داده (مانده مثبت می‌شود)
                $newBalance  -= $netInvoice; // کسر کل مبلغ فاکتور از تراز
                $newBalance  += $paid;       // اضافه کردن مبلغ پرداختی به تراز
                $newTotalIn  += $paid;       // ثبت پول دریافتی در ورودی‌ها
                break;

            case 2: // خرید ما از فروشنده
                // ما کالا گرفتیم (مانده مثبت/طلب او) و پول دادیم (مانده منفی/کم شدن طلب او)
                $newBalance  += $netInvoice;
                $newBalance  -= $paid;
                $newTotalOut += $paid;       // ثبت پول پرداختی ما در خروجی‌ها
                break;

            case 3: // برگشت از فروش (مشتری کالا را پس آورده)
                // طلب مشتری زیاد می‌شود (یا بدهی‌اش کم می‌شود) -> تراز مثبت می‌شود
                // اگر پولی به مشتری برگرداندیم -> از تراز کم و به Total_Out اضافه می‌شود
                $newBalance  += $netInvoice;
                $newBalance  -= $paid;
                $newTotalOut += $paid;
                break;

            case 4: // برگشت از خرید (ما کالا را به فروشنده پس دادیم)
                // طلب فروشنده از ما کم می‌شود -> تراز منفی می‌شود
                // اگر پولمان را پس گرفتیم -> به تراز اضافه و به Total_In اضافه می‌شود
                $newBalance  -= $netInvoice;
                $newBalance  += $paid;
                $newTotalIn  += $paid;
                break;

            case 5: // رسید وجه (فقط پول نقد از مشتری گرفتیم)
                $newTotalIn  += $paid;
                $newBalance  += $paid; // بدهی مشتری را به سمت صفر یا مثبت می‌برد
                break;

            case 6: // پرداخت وجه (فقط پول نقد به فروشنده دادیم)
                $newTotalOut += $paid;
                $newBalance  -= $paid; // طلب فروشنده را کم می‌کند
                break;
        }

        // حذف فیلد احتمالی balance از دیتای ورودی برای جلوگیری از تداخل در insert
        if (isset($data['balance'])) unset($data['balance']);

        // ۱. ثبت در جدول تراکنش‌ها
        $this->db->insert('users_transactions', array_keys($data), $data);

        // ۲. آپدیت نهایی حساب کاربر
        $this->db->update(
            'account_balances',
            (int)$account['id'],
            ['total_in', 'total_out', 'balance'],
            [$newTotalIn, $newTotalOut, $newBalance]
        );
    }

    // update account balance
    public function updateAccountBalance($data)
    {
        if (empty($data['user_id']) || (int)$data['user_id'] === 0) {
            $data['user_id'] = 1;
        }

        $branch_id    = (int)$data['branch_id'];
        $account = $this->db->select('SELECT * FROM account_balances WHERE user_id = ? AND branch_id = ?', [$data['user_id'], $branch_id])->fetch();
        if (!$account) {
            throw new \Exception('کاربر یافت نشد!');
        }

        $balance                 = (float)$account['balance']; // update in all types
        $total_sales             = (float)$account['total_sales']; // update in type 2
        $total_purchase          = (float)$account['total_purchase']; // update in type 1
        $total_sales_returns     = (float)$account['total_sales_returns']; // update in type 4 
        $total_purchase_returns  = (float)$account['total_purchase_returns']; // update in type 3
        $total_payments          = (float)$account['total_payments']; // update in type 1  
        $total_receipts          = (float)$account['total_receipts']; // update in type 2 
        $total_discount_sales    = (float)$account['total_discount_sales']; // update in type 2 
        $total_discount_purchase = (float)$account['total_discount_purchase']; // update in type 1 

        // data defualt
        $total_price  = (float)($data['total_price'] ?? 0);
        $paid_amount  = (float)($data['paid_amount'] ?? 0);
        $discount     = (float)($data['discount'] ?? 0);


        switch ($data['type']) {
            case 1: // (purchase)
                $total_purchase          += $total_price;
                $total_payments          += $paid_amount;
                $total_discount_purchase += $discount;
                $balance                 -= ($total_price - $paid_amount - $discount);
                $update = [
                    'branch_id'             => $branch_id,
                    'total_purchase'        => $total_purchase,
                    'total_payments'        => $total_payments,
                    'total_discount_purchase' => $total_discount_purchase,
                    'balance'               => $balance,
                ];
                break;

            case 2: // (sale)
                $total_sales          += $total_price;
                $total_receipts       += $paid_amount;
                $total_discount_sales += $discount;
                $balance              += ($total_price - $paid_amount - $discount);
                $update = [
                    'branch_id'            => $branch_id,
                    'total_sales'          => $total_sales,
                    'total_receipts'       => $total_receipts,
                    'total_discount_sales' => $total_discount_sales,
                    'balance'              => $balance,
                ];
                break;

            case 3: // (purchase return)
                $total_purchase_returns += $total_price;
                $total_receipts         += $paid_amount;
                $balance                += ($total_price - $paid_amount);
                $update = [
                    'branch_id'              => $branch_id,
                    'total_purchase_returns' => $total_purchase_returns,
                    'total_receipts'         => $total_receipts,
                    'balance'                => $balance,
                ];
                break;

            case 4: // (sale return)
                $total_sales_returns += $total_price;
                $total_payments     += $paid_amount;
                $balance            -= ($total_price - $paid_amount - $discount);
                $update = [
                    'branch_id'          => $branch_id,
                    'total_sales_returns' => $total_sales_returns,
                    'total_payments'     => $total_payments,
                    'balance'            => $balance,
                ];
                break;

            default:
                throw new \Exception('نوع تراکنش نامعتبر است (updateAccountBalance)');
        }

        $update['user_id'] = $data['user_id'];
        $update['last_transaction_at'] = date('Y-m-d');

        $this->db->update(
            'account_balances',
            $account['id'],
            array_keys($update),
            $update
        );
    }
}
