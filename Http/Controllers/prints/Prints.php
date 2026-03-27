<?php

namespace App;

class Prints extends App
{
    // sale for print
    public function salePrintItem($id)
    {
        $this->middleware(true, true, 'general');

        $branchId = (int)$this->getBranchId();

        // 1. تنظیمات ظاهری بِل (لوگو، آدرس، تلفن شعبه)
        $factor_infos = $this->db->select(
            'SELECT * FROM factor_settings WHERE branch_id = ?',
            [$branchId]
        )->fetch();

        // 2. اطلاعات اصلی بِل (شماره بِل، تاریخ، تخفیف کلی، نام مشتری)
        $invoice_infos = $this->db->select(
            "
            SELECT 
                i.*, 
                u.user_name AS user_name, 
                u.phone AS user_mobile 
            FROM invoices i
            LEFT JOIN users u ON i.user_id = u.id
            WHERE i.id = ? AND i.branch_id = ?",
            [$id, $branchId]
        )->fetch();

        if (!$invoice_infos) {
            require_once BASE_PATH . '/404.php';
            exit();
        }

        // 3. آیتم‌های داخل بِل به همراه جزئیات محصول
        $invoice_items = $this->db->select(
            'SELECT 
            sii.*, 
            p.product_name, 
            p.package_type, 
            p.unit_type,
            p.quantity_in_pack
            FROM invoice_items sii
            LEFT JOIN products p ON p.id = sii.product_id
            WHERE sii.invoice_id = ?',
            [$id]
        )->fetchAll();

        // 4. محاسبات نهایی برای نمایش در انتهای بِل
        $subTotal = 0;
        foreach ($invoice_items as $item) {
            $subTotal += (float)$item['item_total_price']; // جمع مبلغ قبل از تخفیف کلی
        }

        $discount = (float)($invoice_infos['discount'] ?? 0);
        $tax = (float)($invoice_infos['tax'] ?? 0); // اگر مالیات داری
        $finalTotal = $subTotal - $discount + $tax;

        // 5. تبدیل تاریخ میلادی به شمسی برای چاپ (اگر تابع jdate را داری)
        $printDate = date('Y/m/d');
        if (function_exists('jdate')) {
            $printDate = jdate('Y/m/d H:i', strtotime($invoice_infos['created_at']));
        }

        $debtor = $this->db->select(
            'SELECT debtor FROM account_balances WHERE user_id = ? AND branch_id = ?',
            [$invoice_infos['user_id'], $branchId]
        )->fetch();

        // ارسال داده‌ها به ویو برای رندر خروجی
        include_once(BASE_PATH . '/resources/views/app/prints/sale-invoice/sale-print.php');
    }

    ///////// financials print //////////
    // financial Print Item after added
    public function financialPrintItem($id)
    {
        $this->middleware(true, true, 'general', true);
        $branchId = (int)$this->getBranchId();

        $factor_infos = $this->db->select(
            'SELECT * FROM factor_settings WHERE branch_id = ?',
            [$branchId]
        )->fetch();

        // توجه: نام جدول و متغیر دقیقاً مطابق کد شما (cash_transactions)
        $transaction = $this->db->select(
            "SELECT 
            ct.*, 
            u.user_name AS user_name, 
            u.phone AS phone,
            u.address AS `address`,
            cb.name AS box_name
        FROM cash_transactions ct
        LEFT JOIN users u ON ct.user_id = u.id
        LEFT JOIN cash_boxes cb ON (ct.from_cash_id = cb.id OR ct.to_cash_id = cb.id)
        WHERE ct.id = ? AND ct.branch_id = ?",
            [$id, $branchId]
        )->fetch();

        if (!$transaction) {
            require_once BASE_PATH . '/404.php';
            exit();
        }

        // فراخوانی متد جدید برای محاسبه مانده قبل (بر اساس زمان ثبت تراکنش)
        // نام متغیرها حفظ شده تا فرانت به هم نریزد
        $oldBalance = $this->getCalculatedBalance(
            $transaction['user_id'],
            $branchId,
            $transaction['created_at']
        );

        $amount = (float)$transaction['amount'];
        $type = (int)$transaction['type'];

        // محاسبه مانده جدید (بعد از تراکنش) بر اساس منطق حسابداری شما
        if ($type == 5) { // رسید (ورودی) -> تراز را مثبت می‌کند
            $newBalance = $oldBalance + $amount;
        } elseif ($type == 6) { // پرداخت (خروجی) -> تراز را منفی می‌کند
            $newBalance = $oldBalance - $amount;
        } else {
            $newBalance = $oldBalance;
        }

        // ارسال به فایل ویو بدون تغییر در ساختار
        include_once(BASE_PATH . '/resources/views/app/prints/financials/financial-print.php');
        exit();
    }

    // genral items print
    public function itemPrint($id)
    {
        $this->middleware(true, true, 'general');

        $this->flashMessageId('success', '', $id);
    }

    // sale for print
    public function saleSinglePrint($id)
    {
        $this->middleware(true, true, 'general', true);

        $userInfos = $this->currentUser();

        $branchId = $this->getBranchId();

        $factor_infos = $this->db->select('SELECT * FROM factor_settings WHERE branch_id = ?', [$branchId])->fetch();

        $sale_invoice_print = $this->db->select(
            'SELECT 
            si.*,
            u.user_name,
            u.address,
            u.phone,
            ca.balance AS balance
            FROM invoices si
            LEFT JOIN users u ON u.id = si.user_id
            LEFT JOIN account_balances ca ON ca.user_id = si.user_id AND ca.branch_id = si.branch_id
            WHERE si.id = ?',
            [$id]
        )->fetch();
        if (!$sale_invoice_print) {
            require_once BASE_PATH . '/404.php';
            exit();
        }

        $invoice_data = $this->db->select(
            'SELECT sii.*, p.package_type, p.unit_type
         FROM invoice_items sii
         LEFT JOIN products p ON p.id = sii.product_id
         WHERE sii.invoice_id = ?',
            [$id]
        )->fetchAll();

        include_once('resources/views/app/prints/sale-invoice/sale-single-print.php');
        // require_once(BASE_PATH . '/resources/views/app/prints/print-item.php');
    }




    /////////// invoices print ////////////

    public function invoicePrint($id)
    {
        $this->middleware(true, true, 'general', true);
        $branchId = (int)$this->getBranchId();

        $factor_infos = $this->db->select(
            'SELECT * FROM factor_settings WHERE branch_id = ?',
            [$branchId]
        )->fetch();

        $invoice_infos = $this->db->select(
            "SELECT i.*, u.user_name AS user_name, u.phone AS user_mobile, u.address AS user_address 
        FROM invoices i
        LEFT JOIN users u ON i.user_id = u.id
        WHERE i.id = ? AND i.branch_id = ?",
            [$id, $branchId]
        )->fetch();

        if (!$invoice_infos) {
            require_once BASE_PATH . '/404.php';
            exit();
        }

        $invoice_items = $this->db->select(
            'SELECT sii.*, p.product_name, p.package_type, p.unit_type, p.quantity_in_pack
        FROM invoice_items sii
        LEFT JOIN products p ON p.id = sii.product_id
        WHERE sii.invoice_id = ?',
            [$id]
        )->fetchAll();

        $userId = $invoice_infos['user_id'];
        $invoiceTime = $invoice_infos['created_at'];

        $oldBalanceQuery = $this->db->select(
            "SELECT SUM(
            CASE 
                WHEN transaction_type = 5 THEN -paid_amount
                WHEN transaction_type IN (1, 4) THEN -(total_amount - discount - paid_amount)
                
                WHEN transaction_type = 6 THEN paid_amount
                WHEN transaction_type IN (2, 3) THEN (total_amount - discount - paid_amount)
                
                ELSE 0 
            END
         ) as balance 
         FROM users_transactions 
         WHERE user_id = ? AND branch_id = ? AND status = 1 
         AND created_at < ?",
            [$userId, $branchId, $invoiceTime]
        )->fetch();

        $oldBalance = (float)($oldBalanceQuery['balance'] ?? 0.0);

        $subTotal = 0;
        foreach ($invoice_items as $item) {
            $subTotal += (float)$item['item_total_price'];
        }

        $discount   = (float)($invoice_infos['discount'] ?? 0);
        $tax        = (float)($invoice_infos['tax'] ?? 0);
        $finalTotal = $subTotal - $discount + $tax;
        $paidAmount = (float)($invoice_infos['paid_amount'] ?? 0);

        $invoiceRemaining = $finalTotal - $paidAmount;
        $type = (int)$invoice_infos['invoice_type'];

        if ($type == 1 || $type == 4) {
            $currentBalanceValue = $oldBalance - $invoiceRemaining;
        } else {
            $currentBalanceValue = $oldBalance + $invoiceRemaining;
        }

        $displayOldBalance = abs($oldBalance);
        $displayInvoiceRemaining = abs($invoiceRemaining);
        $displayTotalBalance = abs($currentBalanceValue);
        $balanceStatus = $currentBalanceValue >= 0 ? 'طلبکار' : 'بدهکار';

        $thisTransaction = (array)$invoice_infos;

        include_once(BASE_PATH . '/resources/views/app/prints/invoices-print/invoice-print.php');
        exit();
    }



    // management of years page
    public function saleInvoicePrint($id)
    {
        $this->middleware(true, true, 'general', true);

        $sale_invoice_print = $this->db->select(
            'SELECT 
            si.*,
            u.user_name,
            u.address,
            u.phone,
            ca.balance AS account_balance
         FROM invoices si
         LEFT JOIN users u ON u.id = si.user_id
         LEFT JOIN account_balances ca ON ca.user_id = si.user_id AND ca.branch_id = si.branch_id
         WHERE si.id = ?',
            [$id]
        )->fetch();

        if (!$sale_invoice_print) {
            require_once BASE_PATH . '/404.php';
            exit();
        }

        $invoice_data = $this->db->select(
            'SELECT sii.*, p.package_type, p.unit_type
         FROM invoice_items sii
         LEFT JOIN products p ON p.id = sii.product_id
         WHERE sii.invoice_id = ?',
            [$id]
        )->fetchAll();

        $print = true;
        require_once(BASE_PATH . '/resources/views/app/sales/add-sale.php');
    }

    // print invoice
    public function print()
    {
        $this->middleware(true, true, 'general', true);
        $branchId = $this->getBranchId();

        $factor_infos = $this->db->select('SELECT * FROM factor_settings WHERE branch_id = ?', [$branchId])->fetch();

        if (!$branchId) {
            require_once(BASE_PATH . '/404.php');
            exit();
        }

        require_once(BASE_PATH . '/resources/views/app/prints/invoice.php');
    }
}
