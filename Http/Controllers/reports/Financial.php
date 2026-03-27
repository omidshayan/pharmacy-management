<?php

namespace App;

require_once 'Http/Controllers/App.php';

class Financial extends App
{
    // financial summary reports
    public function financialSummary()
    {
        $this->middleware(true, true, 'students', true);
        $financial_summary = $this->db->select('SELECT * FROM financial_summary')->fetch();
        require_once(BASE_PATH . '/resources/views/app/reports/financial/financial-summary.php');
    }

    // details account statement
    public function userAccountStatement($id)
    {
        $this->middleware(true, true, 'students', true);
        $user = $this->db->select('SELECT * FROM users WHERE id = ?', [$id])->fetch();
        $user_statement = $this->db->select('SELECT * FROM users_transactions WHERE user_id = ?', [$user['id']])->fetchAll();
        // $user_statement = $this->db->select('SELECT * FROM account_balances WHERE user_id = ?', [$user['id']])->fetchAll();
        require_once(BASE_PATH . '/resources/views/app/account-statement/user-account-statement.php');
    }

    // cardex product
    public function productCardex($productId)
    {
        $this->middleware(true, true, 'general');
        $branchId = (int)$this->getBranchId();

        $product = $this->db->select("SELECT id, product_name, unit_type, package_type, quantity_in_pack FROM products WHERE id = ?", [$productId])->fetch();

        if (!$product) {
            $this->flashMessage('error', 'محصول یافت نشد!');
            return;
        }

        // اضافه کردن p.quantity_in_pack به SELECT
        $movements = $this->db->select("
        SELECT 
            m.*, 
            w.warehouse_name,
            i.invoice_number,
            i.id AS real_invoice_id,
            p.quantity_in_pack -- گرفتن ضریب بسته از جدول محصولات
            FROM inventory_movements AS m
            LEFT JOIN warehouses AS w ON m.warehouse_id = w.id
            LEFT JOIN invoices AS i ON m.invoice_id = i.id
            LEFT JOIN products AS p ON m.product_id = p.id
            WHERE m.product_id = ? AND m.branch_id = ?
            ORDER BY m.movement_date ASC, m.id ASC
        ", [$productId, $branchId])->fetchAll();

        $cardexData = [];
        $runningBalance = 0;

        foreach ($movements as $m) {
            $qty = (float)$m['total_unit_qty'];
            // ضریب بسته را از ستونی که Join کردیم می‌گیریم
            $qtyInPack = (float)$m['quantity_in_pack'] > 0 ? (float)$m['quantity_in_pack'] : 1;

            $movType = (int)$m['movement_type'];
            $refType = (int)$m['reference_type'];

            // منطق Fallback: اگر قیمت واحد صفر بود، از قیمت بسته محاسبه کن
            $uBuy = (float)$m['unit_price_buy'];
            if ($uBuy <= 0 && (float)$m['package_price_buy'] > 0) {
                $uBuy = (float)$m['package_price_buy'] / $qtyInPack;
            }

            $uSell = (float)$m['unit_price_sell'];
            if ($uSell <= 0 && (float)$m['package_price_sell'] > 0) {
                $uSell = (float)$m['package_price_sell'] / $qtyInPack;
            }

            // تعیین قیمت نمایش (ورودی: قیمت خرید | خروجی: قیمت فروش)
            $displayUnitPrice = ($movType == 2) ? $uBuy : $uSell;
            $totalRowPrice = $qty * $displayUnitPrice;

            if ($movType == 2) {
                $runningBalance += $qty;
                $colorClass = 'text-success';
                switch ($refType) {
                    case 2:
                        $typeLabel = 'ورود <span class="badge bg-success fs12">خرید</span>';
                        break;
                    case 3:
                        $typeLabel = 'ورود <span class="badge bg-info fs12">برگشت از فروش</span>';
                        break;
                    default:
                        $typeLabel = 'ورود';
                }
            } else {
                $runningBalance -= $qty;
                $colorClass = 'text-danger';
                switch ($refType) {
                    case 1:
                        $typeLabel = 'خروج <span class="badge bg-red fs12">فروش</span>';
                        break;
                    case 4:
                        $typeLabel = 'خروج <span class="badge bg-orange text-dark fs12">برگشت از خرید</span>';
                        break;
                    default:
                        $typeLabel = 'خروج';
                }
            }

            $cardexData[] = [
                'date'           => $m['movement_date'],
                'invoice_number' => $m['invoice_number'] ?? '---',
                'idd'     => $m['invoice_id'],
                'invoice_id'     => $m['real_invoice_id'],
                'type_label'     => $typeLabel,
                'warehouse'      => ($m['warehouse_id'] == 99099) ? 'رزرو منفی' : ($m['warehouse_name'] ?? 'نامشخص'),
                'qty'            => $qty,
                'unit_price'     => $displayUnitPrice,
                'total_price'    => $totalRowPrice,
                'color'          => $colorClass,
                'balance'        => $runningBalance,
                'who'            => $m['who_it']
            ];
        }

        require_once(BASE_PATH . '/resources/views/app/reports/cardex.php');
    }
}
