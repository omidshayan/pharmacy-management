<?php

namespace App;

require_once 'Http/Controllers/App.php';
require_once 'Http/Models/Invoice.php';
require_once 'Http/Models/Transaction.php';
require_once 'Http/Models/Calendar.php';
require_once 'Http/Models/Reports.php';

use Models\Invoice\Invoice;
use Models\Transaction\Transaction;
use Models\Calendar\Calendar;
use Models\Reports\Reports;

class Invoices extends App
{
    private $invoice;
    private $transaction;
    private $reports;
    private $calendar;

    public function __construct()
    {
        parent::__construct();
        $this->invoice = new Invoice();
        $this->transaction = new Transaction();
        $this->calendar = new Calendar();
        $this->reports = new Reports();
    }

    // edit invoice store
    public function editInvoiceStore($request, $id)
    {
        $this->middleware(true, true, 'students', true, $request, true);

        $this->normalizeFloatFields($request, 'total_price', 'paid_amount', 'discount');

        if ($request['paid_amount'] > $request['total_price']) {
            $this->flashMessage('error', 'مبلغ پرداختی نمی تواند بیشتر از مبلغ بِل باشد!');
        }

        // Check if invoice already exists in the database
        $invoice = $this->invoice->getInvoice($request['invoice_id']);
        if (!$invoice) {
            require_once(BASE_PATH . '/404.php');
            exit();
        }

        $request = $this->validateInputs($request, ['buy_inv_img' => false]);

        // get invoice items
        $invoice_items = $this->invoice->getInvoiceItems($invoice['id']);
        if (!$invoice_items) {
            $this->flashMessage('error', 'بِل مورد نظر خالی است!');
        }
        echo 'comming...';
    }

    // update buy invoice item
    public function updateInvoiceItem($request, $id)
    {
        $this->middleware(true, true, 'general');

        $package_qty = isset($request['package_qty']) ? floatval($request['package_qty']) : 0;
        $unit_qty = isset($request['unit_qty']) ? floatval($request['unit_qty']) : 0;

        $item = $this->db->select('SELECT * FROM invoice_items WHERE id = ?', [$id])->fetch();
        if (!$item) {
            $this->send_json_response(false, 'آیتم یافت نشد');
            return;
        }

        $package_price_buy = isset($request['package_price_buy']) ? floatval($request['package_price_buy']) : floatval($item['package_price_buy']);
        $package_price_sell = isset($request['package_price_sell']) ? floatval($request['package_price_sell']) : floatval($item['package_price_sell']);
        $unit_price_buy = isset($request['unit_price_buy']) ? floatval($request['unit_price_buy']) : (isset($item['unit_price_buy']) ? floatval($item['unit_price_buy']) : null);
        $unit_price_sell = isset($request['unit_price_sell']) ? floatval($request['unit_price_sell']) : (isset($item['unit_price_sell']) ? floatval($item['unit_price_sell']) : null);

        $quantity_in_pack = floatval($item['quantity_in_pack']);

        $total_quantity = ($package_qty * $quantity_in_pack) + $unit_qty;

        $total_price = ($package_qty * $package_price_buy) + ($unit_qty * ($unit_price_buy ?? ($package_price_buy / max($quantity_in_pack, 1))));

        $update_data = [
            'package_qty' => $package_qty,
            'unit_qty' => $unit_qty,
            'item_total_price' => $total_price,
            'package_price_buy' => $package_price_buy,
            'package_price_sell' => $package_price_sell,
            'unit_price_buy' => $unit_price_buy,
            'unit_price_sell' => $unit_price_sell,
            'quantity' => $total_quantity,
        ];

        try {
            $this->db->beginTransaction();

            $updated = $this->db->update(
                'invoice_items',
                $id,
                array_keys($update_data),
                $update_data
            );

            if (!$updated) {
                throw new \Exception('خطا در به‌روزرسانی رکورد');
            }

            $this->db->commit();
            $this->send_json_response(true, 'بروزرسانی با موفقیت انجام شد');
        } catch (\Exception $e) {
            $this->db->rollBack();
            $this->send_json_response(false, 'خطا در بروزرسانی رکورد: ' . $e->getMessage());
        }
    }

    // update sale invoice item
    public function updateSaleInvoiceItem($request, $id)
    {
        $this->middleware(true, true, 'general');

        $package_qty = isset($request['package_qty']) ? floatval($request['package_qty']) : 0;
        $unit_qty = isset($request['unit_qty']) ? floatval($request['unit_qty']) : 0;

        $item = $this->db->select('SELECT * FROM invoice_items WHERE id = ?', [$id])->fetch();
        if (!$item) {
            $this->send_json_response(false, 'آیتم یافت نشد');
            return;
        }

        $package_price_buy = isset($request['package_price_buy']) ? floatval($request['package_price_buy']) : floatval($item['package_price_buy']);
        $package_price_sell = isset($request['package_price_sell']) ? floatval($request['package_price_sell']) : floatval($item['package_price_sell']);
        $unit_price_buy = isset($request['unit_price_buy']) ? floatval($request['unit_price_buy']) : (isset($item['unit_price_buy']) ? floatval($item['unit_price_buy']) : null);
        $unit_price_sell = isset($request['unit_price_sell']) ? floatval($request['unit_price_sell']) : (isset($item['unit_price_sell']) ? floatval($item['unit_price_sell']) : null);

        $quantity_in_pack = floatval($item['quantity_in_pack']);
        $total_quantity = ($package_qty * $quantity_in_pack) + $unit_qty;
        $total_price = ($package_qty * $package_price_sell) + ($unit_qty * ($unit_price_sell ?? ($package_price_sell / max($quantity_in_pack, 1))));

        $update_data = [
            'package_qty' => $package_qty,
            'unit_qty' => $unit_qty,
            'item_total_price' => $total_price,
            'package_price_buy' => $package_price_buy,
            'package_price_sell' => $package_price_sell,
            'unit_price_buy' => $unit_price_buy,
            'unit_price_sell' => $unit_price_sell,
            'quantity' => $total_quantity,
        ];

        try {
            $this->db->beginTransaction();

            $updated = $this->db->update(
                'invoice_items',
                $id,
                array_keys($update_data),
                $update_data
            );

            if ($updated === false) {
                throw new \Exception('خطا در به‌روزرسانی رکورد');
            }

            $this->db->commit();
            $this->send_json_response(true, 'بروزرسانی با موفقیت انجام شد');
        } catch (\Exception $e) {
            $this->db->rollBack();
            $this->send_json_response(false, 'خطا: ' . $e->getMessage());
        }
    }

    // invoices details
    public function invoiceDetails($id)
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();

        $invoice = $this->invoice->getInvoiceDetails($id);

        $user_infos = $invoice['user_infos'];
        $invoice_items = $invoice['items'];
        $invoice = $invoice['invoice'];

        $profits = $this->getInvoiceProfit($id);

        require_once(BASE_PATH . '/resources/views/app/invoices/invoice-details.php');
        exit();
    }

    // invoice profit
    public function getInvoiceProfit($invoiceId)
    {
        $branchId = (int)$this->getBranchId();

        $movements = $this->db->select(
            "SELECT m.*, i.quantity_in_pack 
         FROM inventory_movements m
         JOIN products i ON m.product_id = i.id
         WHERE m.invoice_id = ? AND m.branch_id = ? AND m.movement_type = 1",
            [$invoiceId, $branchId]
        )->fetchAll();

        $totalProfit = 0;
        $packageProfit = 0;
        $unitProfit = 0;

        foreach ($movements as $m) {
            $qtyInPack = (float)$m['quantity_in_pack'];
            $totalUnits = (float)$m['total_unit_qty'];

            $uSell = (float)$m['unit_price_sell'];
            $uBuy  = (float)$m['unit_price_buy'];

            if ($uSell <= 0 && $qtyInPack > 0) {
                $uSell = (float)$m['package_price_sell'] / $qtyInPack;
            }
            if ($uBuy <= 0 && $qtyInPack > 0) {
                $uBuy = (float)$m['package_price_buy'] / $qtyInPack;
            }

            $rowProfit = ($uSell - $uBuy) * $totalUnits;
            $totalProfit += $rowProfit;

            if ($qtyInPack > 0 && $totalUnits >= $qtyInPack) {
                $numPackages = floor($totalUnits / $qtyInPack);
                $remainingUnits = fmod($totalUnits, $qtyInPack);

                $packageProfit += $numPackages * ((float)$m['package_price_sell'] - (float)$m['package_price_buy']);

                if ($remainingUnits > 0) {
                    $unitProfit += $remainingUnits * ($uSell - $uBuy);
                }
            } else {
                $unitProfit += $rowProfit;
            }
        }

        return [
            'total_profit'   => round($totalProfit, 2),
            'package_profit' => round($packageProfit, 2),
            'unit_profit'    => round($unitProfit, 2),
            'item_count'     => count($movements)
        ];
    }

    ///////////////// editing invoice /////////////////

    // edit invoice page
    public function editInvoice($id)
    {
        $this->middleware(true, true, 'general', true);

        $branchId = (int)$this->getBranchId();

        $currentUserId = $this->currentUser();

        $sale_invoice = $this->db->select(
            'SELECT * FROM invoices WHERE id = ? AND branch_id = ?',
            [(int)$id, $branchId]
        )->fetch();

        if ($sale_invoice) {
            $invoiceId = (int)$sale_invoice['id'];

            $existingDrafts = $this->db->select(
                'SELECT id FROM edit_draft_items WHERE invoice_id = ?',
                [$invoiceId]
            )->fetchAll();

            if ($existingDrafts) {
                foreach ($existingDrafts as $draft) {
                    $this->db->delete('edit_draft_items', $draft['id']);
                }
            }

            $originalItems = $this->db->select(
                'SELECT * FROM invoice_items WHERE invoice_id = ?',
                [$invoiceId]
            )->fetchAll();

            foreach ($originalItems as $item) {
                $draftData = [
                    'original_item_id'   => $item['id'],
                    'invoice_id'         => $item['invoice_id'],
                    'product_id'         => $item['product_id'],
                    'product_name'       => $item['product_name'],
                    'package_qty'        => $item['package_qty'],
                    'unit_qty'           => $item['unit_qty'],
                    'quantity'           => $item['quantity'],
                    'quantity_in_pack'   => $item['quantity_in_pack'],
                    'package_price_buy'  => $item['package_price_buy'],
                    'package_price_sell' => $item['package_price_sell'],
                    'unit_price_buy'     => $item['unit_price_buy'],
                    'unit_price_sell'    => $item['unit_price_sell'],
                    'discount'           => $item['discount'],
                    'item_total_price'   => $item['item_total_price'],
                    'who_it'             => $currentUserId['name'],
                    'user_id'             => $currentUserId['id'],
                ];

                $this->db->insert('edit_draft_items', array_keys($draftData), $draftData);
            }

            $seller = $this->db->select('SELECT id, user_name, phone FROM users WHERE id = ? AND status = 1', [$sale_invoice['user_id']])->fetch();
            $cash_boxes = $this->db->select('SELECT id, name FROM cash_boxes WHERE status = 1 AND branch_id = ?', [$branchId])->fetchAll();
        } else {
            require_once BASE_PATH . '/404.php';
            exit();
        }

        require_once(BASE_PATH . '/resources/views/app/sales/edit-invoice/edit-invoice.php');
    }

    // get invoice items ajax whit id
    public function getEditInvoiceItemsAjax($id)
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();

        $invoice = $this->db->select('SELECT * FROM invoices WHERE id = ?', [$id])->fetch();

        if (!$invoice) {
            $this->send_json_response(true, 'empty', [
                'items' => []
            ]);
            exit();
        }

        $items = $this->db->select("SELECT 
                ii.*,
                COALESCE(SUM(inv.quantity),0) AS total_stock
            FROM edit_draft_items ii
            LEFT JOIN inventory inv 
                ON inv.product_id = ii.product_id
            WHERE ii.invoice_id = ?
            GROUP BY ii.id
        ", [$invoice['id']])->fetchAll();

        $this->send_json_response(true, 'ok', [
            'items' => $items,
            'invoice_id' => $invoice['id']
        ]);

        exit();
    }

    // change values live
    public function updateEditInvoiceItem($request, $id)
    {
        $this->middleware(true, true, 'general');

        // ۱. گرفتن اطلاعات از جدول پیش‌نویس (به جای جدول اصلی)
        $item = $this->db->select('SELECT * FROM edit_draft_items WHERE id = ?', [$id])->fetch();

        if (!$item) {
            $this->send_json_response(false, 'آیتم در پیش‌نویس یافت نشد');
            return;
        }

        $package_qty = isset($request['package_qty']) ? floatval($request['package_qty']) : 0;
        $unit_qty = isset($request['unit_qty']) ? floatval($request['unit_qty']) : 0;

        // استفاده از مقادیر موجود در پیش‌نویس در صورت عدم ارسال مقدار جدید
        $package_price_buy = isset($request['package_price_buy']) ? floatval($request['package_price_buy']) : floatval($item['package_price_buy']);
        $package_price_sell = isset($request['package_price_sell']) ? floatval($request['package_price_sell']) : floatval($item['package_price_sell']);
        $unit_price_buy = isset($request['unit_price_buy']) ? floatval($request['unit_price_buy']) : floatval($item['unit_price_buy']);
        $unit_price_sell = isset($request['unit_price_sell']) ? floatval($request['unit_price_sell']) : floatval($item['unit_price_sell']);

        $quantity_in_pack = floatval($item['quantity_in_pack']);
        $total_quantity = ($package_qty * $quantity_in_pack) + $unit_qty;

        // محاسبه قیمت کل ردیف
        $total_price = ($package_qty * $package_price_sell) + ($unit_qty * ($unit_price_sell ?? ($package_price_sell / max($quantity_in_pack, 1))));

        $update_data = [
            'package_qty'        => $package_qty,
            'unit_qty'           => $unit_qty,
            'quantity'           => $total_quantity,
            'item_total_price'   => $total_price,
            'package_price_buy'  => $package_price_buy,
            'package_price_sell' => $package_price_sell,
            'unit_price_buy'     => $unit_price_buy,
            'unit_price_sell'    => $unit_price_sell,
        ];

        try {
            // آپدیت روی جدول پیش‌نویس با متد update خودتان
            $updated = $this->db->update(
                'edit_draft_items',
                $id,
                array_keys($update_data),
                $update_data
            );

            if ($updated === false) {
                throw new \Exception('خطا در به‌روزرسانی پیش‌نویس');
            }

            $this->send_json_response(true, 'تغییرات در پیش‌نویس ثبت شد');
        } catch (\Exception $e) {
            $this->send_json_response(false, 'خطا: ' . $e->getMessage());
        }
    }

    // delete product from cart
    public function editDeleteProductCart($id)
    {
        $this->middleware(true, true, 'general', true);

        if (!is_numeric($id)) {
            $this->flashMessage('error', 'لطفا اطلاعات درست ارسال نمائید!');
        }

        $product_cart = $this->db->select('SELECT id FROM edit_draft_items WHERE `id` = ?', [$id])->fetch();

        if (!$product_cart) {
            require_once(BASE_PATH . '/404.php');
            exit;
        }

        $result = $this->db->delete('edit_draft_items', $id);

        if ($result) {
            $this->send_json_response(true, _added, [
                'success' => true
            ]);
        } else {
            $this->flashMessage('error', _error);
        }
    }

    // store product
    public function editInvoiceItemStore($request)
    {
        $this->middleware(true, true, 'general');

        $invoiceId = (int)$request['invoice_id'];
        $productId = (int)$request['product_id'];
        $userInfo = $this->currentUser();
        $branchId = $this->getBranchId();

        $request = $this->cleanNumericFields($request, [
            'unit_qty',
            'package_qty',
            'package_price_buy',
            'package_price_sell',
            'unit_price_buy',
            'unit_price_sell'
        ]);

        $product_info = $this->db->select('SELECT unit_type, quantity_in_pack FROM products WHERE id = ?', [$productId])->fetch();

        $is_unit = !is_null($product_info['unit_type']);
        $unit_qty = $is_unit ? 1 : 0;
        $package_qty = $is_unit ? 0 : 1;
        $item_total_price = $is_unit ? $request['unit_price_sell'] : $request['package_price_sell'];

        $exist_in_draft = $this->db->select(
            'SELECT id, quantity, package_qty, unit_qty, item_total_price 
         FROM edit_draft_items 
         WHERE invoice_id = ? AND product_id = ? AND user_id = ?',
            [$invoiceId, $productId, $userInfo['id']]
        )->fetch();

        if (!$exist_in_draft) {
            $draft_item = [
                'original_item_id'   => null,
                'invoice_id'         => $invoiceId,
                'product_id'         => $productId,
                'product_name'       => $request['product_name'],
                'unit_qty'           => $unit_qty,
                'package_qty'        => $package_qty,
                'quantity'           => 1,
                'quantity_in_pack'   => $product_info['quantity_in_pack'],
                'package_price_buy'  => $request['package_price_buy'],
                'package_price_sell' => $request['package_price_sell'],
                'unit_price_buy'     => $request['unit_price_buy'],
                'unit_price_sell'    => $request['unit_price_sell'],
                'item_total_price'   => $item_total_price,
                'user_id'            => $userInfo['id'],
                'who_it'             => $userInfo['name'],
            ];

            $this->db->insert('edit_draft_items', array_keys($draft_item), $draft_item);
        } else {
            $update_data = [
                'quantity'           => $exist_in_draft['quantity'] + 1,
                'package_qty'        => $exist_in_draft['package_qty'] + $package_qty,
                'unit_qty'           => $exist_in_draft['unit_qty'] + $unit_qty,
                'item_total_price'   => $exist_in_draft['item_total_price'] + $item_total_price,
                'package_price_sell' => $request['package_price_sell'],
                'unit_price_sell'    => $request['unit_price_sell'],
            ];

            $this->db->update('edit_draft_items', $exist_in_draft['id'], array_keys($update_data), $update_data);
        }

        $invoice_items = $this->db->select(
            'SELECT * FROM edit_draft_items WHERE invoice_id = ? AND user_id = ?',
            [$invoiceId, $userInfo['id']]
        )->fetchAll();

        $this->send_json_response(true, _added, [
            'items' => $invoice_items,
            'invoice_id' => $invoiceId,
        ]);
        exit;
    }

    // close edit invoice 
    public function closeEditInvoiceStore($request)
    {
        $this->middleware(true, true, 'general');

        $this->normalizeFloatFields($request, 'total_price', 'paid_amount', 'discount');

        $total_price       = isset($request['total_price']) ? floatval($request['total_price']) : 0;
        $sale_discount     = isset($request['discount']) ? floatval($request['discount']) : 0;
        $sale_paid_amount  = isset($request['paid_amount']) ? floatval($request['paid_amount']) : 0;
        $invoiceId         = (int)$request['invoice_id'];
        $branchId          = (int)$this->getBranchId();
        $userInfo          = $this->currentUser();

        $netRemaining = $total_price - $sale_discount - $sale_paid_amount;

        try {
            $this->db->beginTransaction();

            $invoice = $this->invoice->getInvoice($invoiceId, $branchId);
            if (!$invoice) throw new \Exception('Invoice not found');

            // ۱. معکوس کردن اثرات انبار (برگرداندن موجودی فاکتور قبلی به ردیف‌های خرید)
            $old_movements = $this->db->select(
                "SELECT * FROM inventory_movements WHERE invoice_id = ? AND movement_type = 1",
                [$invoiceId]
            )->fetchAll();

            foreach ($old_movements as $old_mov) {
                $source_batch = $this->db->select(
                    "SELECT * FROM inventory_movements WHERE product_id = ? AND branch_id = ? AND movement_type = 2 AND warehouse_id = ? AND package_price_buy = ? LIMIT 1",
                    [$old_mov['product_id'], $branchId, $old_mov['warehouse_id'], $old_mov['package_price_buy']]
                )->fetch();

                if ($source_batch) {
                    $restored_qty = (float)$source_batch['remaining_qty'] + (float)$old_mov['total_unit_qty'];
                    $this->db->update('inventory_movements', $source_batch['id'], ['remaining_qty'], [$restored_qty]);
                }

                $existingInv = $this->db->select("SELECT * FROM inventory WHERE product_id = ? AND branch_id = ? LIMIT 1", [$old_mov['product_id'], $branchId])->fetch();
                if ($existingInv) {
                    $this->db->update('inventory', $existingInv['id'], ['quantity'], [(float)$existingInv['quantity'] + (float)$old_mov['total_unit_qty']]);
                }
            }

            // ۲. حذف ردیف‌های قبلی (پاکسازی کامل برای جلوگیری از تکرار در دفعات بعدی ویرایش)
            $this->db->delete('inventory_movements', 'invoice_id', $invoiceId);
            $this->db->delete('invoice_items', 'invoice_id', $invoiceId);
            $this->db->delete('users_transactions', 'ref_id', $invoiceId);
            $this->db->delete('cash_transactions', 'ref_id', $invoiceId);

            // ۳. انتقال از Draft به Invoice_items و اجرای منطق فروش جدید
            $draft_items = $this->db->select("SELECT * FROM edit_draft_items WHERE invoice_id = ? AND user_id = ?", [$invoiceId, $userInfo['id']])->fetchAll();
            if (!$draft_items) throw new \Exception('Draft is empty');

            foreach ($draft_items as $item) {
                $invoice_item_data = [
                    'branch_id'          => $branchId,
                    'invoice_id'         => $invoiceId,
                    'product_id'         => $item['product_id'],
                    'product_name'       => $item['product_name'],
                    'unit_qty'           => $item['unit_qty'],
                    'package_qty'        => $item['package_qty'],
                    'quantity_in_pack'   => $item['quantity_in_pack'],
                    'package_price_buy'  => $item['package_price_buy'],
                    'package_price_sell' => $item['package_price_sell'],
                    'unit_price_buy'     => $item['unit_price_buy'],
                    'unit_price_sell'    => $item['unit_price_sell'],
                    'item_total_price'   => $item['item_total_price'],
                    'quantity'           => $item['quantity'],
                    'discount'           => $item['discount'] ?? 0
                ];
                $new_item_id = $this->db->insert('invoice_items', array_keys($invoice_item_data), $invoice_item_data);

                // منطق FIFO (کپی دقیق از متد closeSaleInvoiceStore شما)
                $sellQtyTotal = (float)$item['quantity'];
                $fifo_movements = $this->db->select(
                    "SELECT * FROM inventory_movements WHERE product_id = ? AND branch_id = ? AND movement_type = 2 AND remaining_qty > 0 ORDER BY movement_date ASC, id ASC",
                    [$item['product_id'], $branchId]
                )->fetchAll();

                $tempSellQty = $sellQtyTotal;
                if (!empty($fifo_movements)) {
                    foreach ($fifo_movements as $movement) {
                        if ($tempSellQty <= 0) break;
                        $takeQty = min($tempSellQty, (float)$movement['remaining_qty']);
                        $this->db->update('inventory_movements', $movement['id'], ['remaining_qty'], [(float)$movement['remaining_qty'] - $takeQty]);

                        $movData = [
                            'branch_id'          => $branchId,
                            'product_id'         => $item['product_id'],
                            'invoice_id'         => $invoiceId,
                            'invoice_item_id'    => $new_item_id,
                            'movement_type'      => 1,
                            'reference_type'     => 1,
                            'total_unit_qty'     => $takeQty,
                            'remaining_qty'      => 0,
                            'package_price_buy'  => $movement['package_price_buy'],
                            'package_price_sell' => $item['package_price_sell'],
                            'unit_price_buy'     => $movement['unit_price_buy'],
                            'unit_price_sell'    => $item['unit_price_sell'],
                            'movement_date'      => $request['date'],
                            'warehouse_id'       => $movement['warehouse_id'],
                            'who_it'             => $userInfo['name']
                        ];
                        $this->db->insert('inventory_movements', array_keys($movData), $movData);
                        $tempSellQty -= $takeQty;
                    }
                }

                if ($tempSellQty > 0) {
                    $existingInventory = $this->db->select("SELECT * FROM inventory WHERE product_id = ? AND branch_id = ? LIMIT 1", [$item['product_id'], $branchId])->fetch();
                    $extraMov = [
                        'branch_id'          => $branchId,
                        'product_id'         => $item['product_id'],
                        'invoice_id'         => $invoiceId,
                        'invoice_item_id'    => $new_item_id,
                        'movement_type'      => 1,
                        'reference_type'     => 1,
                        'total_unit_qty'     => $tempSellQty,
                        'remaining_qty'      => 0,
                        'package_price_buy'  => $existingInventory['package_price_buy'],
                        'package_price_sell' => $item['package_price_sell'],
                        'unit_price_buy'     => $existingInventory['unit_price_buy'],
                        'unit_price_sell'    => $item['unit_price_sell'],
                        'movement_date'      => $request['date'],
                        'warehouse_id'       => $request['warehouse_id'] ?? 99099,
                        'who_it'             => $userInfo['name']
                    ];
                    $this->db->insert('inventory_movements', array_keys($extraMov), $extraMov);
                }

                $existingInv = $this->db->select("SELECT id, quantity FROM inventory WHERE product_id = ? AND branch_id = ? LIMIT 1", [$item['product_id'], $branchId])->fetch();
                $this->db->update('inventory', $existingInv['id'], ['quantity'], [(float)$existingInv['quantity'] - $sellQtyTotal]);
            }

            $this->transaction->addNewTransaction([
                'branch_id'        => $branchId,
                'ref_id'           => $invoiceId,
                'user_id'          => (int)$request['seller_id'],
                'transaction_type' => 1,
                'total_amount'     => $total_price,
                'discount'         => $sale_discount,
                'paid_amount'      => $sale_paid_amount,
                'transaction_date' => $request['date'],
                'description'      => $request['description'] ?? 'ویرایش بِل ' . $invoiceId,
                'status'           => 1,
                'who_it'           => $userInfo['name'],
                'balance'          => $netRemaining
            ]);

            if ($sale_paid_amount > 0) {
                $this->reports->updateFund([
                    'branch_id'  => $branchId,
                    'to_cash_id' => $request['source'],
                    'amount'     => $sale_paid_amount,
                    'ref_id'     => $invoiceId,
                    'type'       => 1,
                    'user_id'    => (int)$request['seller_id'],
                    'date'       => $request['date'],
                    'source'     => $request['source']
                ]);
            }

            $yearMonth = $this->calendar->getYearMonth();
            $invoice_infos = [
                'total_amount' => $total_price,
                'discount'     => $sale_discount,
                'user_id'      => (int)$request['seller_id'],
                'date'         => $request['date'],
                'paid_amount'  => $sale_paid_amount,
                'description'  => $request['description'] ?? null,
                'year'         => $yearMonth['year'],
                'month'        => $yearMonth['month'],
                'status'       => 2
            ];
            $this->db->update('invoices', $invoiceId, array_keys($invoice_infos), $invoice_infos);

            $this->db->delete('edit_draft_items', 'invoice_id', $invoiceId);
            $this->db->commit();
            $this->flashMessage('success', _success);
        } catch (\Exception $e) {
            $this->db->rollBack();
            $this->send_json_response(false, 'خطا: ' . $e->getMessage());
        }
    }

    // cancel invoice
    public function cancelInvoice($invoiceId)
    {
        $userInfo = $this->currentUser();
        $branchId = (int)$userInfo['branch_id'];

        $invoice = $this->invoice->getInvoice($invoiceId, $branchId);
        if (!$invoice) throw new \Exception('Invoice not found');

        if ((int)$invoice['status'] === 3) {
            $this->flashMessage('error', 'فاکتور قبلاً غیرفعال شده');
            return;
        }

        try {
            $this->db->beginTransaction();

            $movements = $this->db->select(
                "SELECT * FROM inventory_movements WHERE invoice_id = ? AND branch_id = ?",
                [$invoice['id'], $branchId]
            )->fetchAll();

            foreach ($movements as $move) {

                $warehouseId = $move['warehouse_id'];
                $totalUnits  = (float)$move['total_unit_qty'];

                if ($totalUnits <= 0) continue;

                // 🔴 تعیین نوع معکوس
                $reverseType = ($move['movement_type'] == 1) ? 2 : 1;

                // 🔴 ثبت موومنت معکوس
                $this->db->insert('inventory_movements', [
                    'branch_id',
                    'product_id',
                    'invoice_id',
                    'invoice_item_id',
                    'movement_type',
                    'reference_type',
                    'total_unit_qty',
                    'remaining_qty',
                    'movement_date',
                    'warehouse_id',
                    'who_it'
                ], [
                    $branchId,
                    $move['product_id'],
                    $invoice['id'],
                    $move['invoice_item_id'],
                    $reverseType,
                    $invoice['invoice_type'],
                    $totalUnits,
                    0,
                    date('Y-m-d'),
                    $warehouseId,
                    $userInfo['name']
                ]);

                // 🔴 آپدیت inventory
                $inv = $this->db->select(
                    "SELECT id, quantity FROM inventory 
                 WHERE product_id = ? AND branch_id = ? AND warehouse_id = ? LIMIT 1",
                    [$move['product_id'], $branchId, $warehouseId]
                )->fetch();

                if ($inv) {

                    if ($move['movement_type'] == 1) {
                        // قبلاً خروج بوده → حالا برگرده داخل
                        $newQty = (float)$inv['quantity'] + $totalUnits;
                    } else {
                        // قبلاً ورود بوده → حالا کم بشه
                        $newQty = (float)$inv['quantity'] - $totalUnits;

                        if ($newQty < 0) {
                            throw new \Exception('موجودی کافی برای ابطال وجود ندارد');
                        }
                    }

                    $this->db->update('inventory', $inv['id'], ['quantity'], [$newQty]);
                }
            }

            // 🔴 تعیین نوع تراکنش معکوس
            $reverseTransactionType = 0;
            switch ((int)$invoice['invoice_type']) {
                case 1:
                    $reverseTransactionType = 3;
                    break; // فروش → برگشت از فروش
                case 2:
                    $reverseTransactionType = 4;
                    break; // خرید → برگشت از خرید
                case 3:
                    $reverseTransactionType = 1;
                    break; // برگشت فروش → فروش
                case 4:
                    $reverseTransactionType = 2;
                    break; // برگشت خرید → خرید
            }

            // 🔴 ثبت تراکنش معکوس
            $this->transaction->addNewTransaction([
                'branch_id'        => $branchId,
                'ref_id'           => $invoice['id'],
                'user_id'          => $invoice['user_id'],
                'transaction_type' => $reverseTransactionType,
                'total_amount'     => $invoice['total_amount'],
                'discount'         => $invoice['discount'],
                'paid_amount'      => $invoice['paid_amount'],
                'transaction_date' => date('Y-m-d'),
                'description'      => 'ابطال فاکتور شماره ' . $invoice['id'],
                'status'           => 1,
                'who_it'           => $userInfo['name'],
            ]);

            // 🔴 ریورس صندوق (واقعی و دقیق)
            $cashTransactions = $this->db->select(
                "SELECT * FROM cash_transactions WHERE ref_id = ? AND branch_id = ?",
                [$invoice['id'], $branchId]
            )->fetchAll();

            foreach ($cashTransactions as $cash) {

                $sourceId = $cash['source'] ?? $cash['to_cash_id'] ?? null;
                if (!$sourceId) continue;

                // تعیین نوع معکوس صندوق
                $reverseFundType = 0;
                switch ((int)$cash['type']) {
                    case 1:
                        $reverseFundType = 2;
                        break;
                    case 2:
                        $reverseFundType = 1;
                        break;
                    case 3:
                        $reverseFundType = 1;
                        break;
                    case 4:
                        $reverseFundType = 2;
                        break;
                    case 5:
                        $reverseFundType = 6;
                        break;
                    case 6:
                        $reverseFundType = 5;
                        break;
                }

                $this->reports->updateFund([
                    'branch_id'  => $branchId,
                    'to_cash_id' => $sourceId,
                    'amount'     => (float)$cash['amount'],
                    'ref_id'     => $invoice['id'],
                    'type'       => $reverseFundType,
                    'user_id'    => $invoice['user_id'],
                    'date'       => date('Y-m-d'),
                    'source'     => $sourceId,
                ]);
            }

            // 🔴 تغییر وضعیت فاکتور
            $this->db->update('invoices', $invoice['id'], ['status'], [3]);

            $this->db->commit();

            $this->flashMessage('success', 'فاکتور با موفقیت ابطال شد');
        } catch (\Exception $e) {
            $this->db->rollBack();
            $this->flashMessage('error', $e->getMessage());
        }
    }
}
