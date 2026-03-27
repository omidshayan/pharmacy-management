<?php

namespace App;

require_once 'Http/Controllers/App.php';
require_once 'Http/Models/Calendar.php';
require_once 'Http/Models/Invoice.php';
require_once 'Http/Models/User.php';
require_once 'Http/Models/Financial.php';

use Models\Calendar\Calendar;
use Models\Invoice\Invoice;
use Models\User\User;
use Models\Financial\Financial;

class EditInvoiceSale extends App
{
    private $calendar;
    private $invoice;
    private $user;
    private $financial;
    public function __construct()
    {
        parent::__construct();
        $this->calendar = new Calendar();
        $this->invoice = new Invoice();
        $this->user = new User();
        $this->financial = new Financial();
    }

    // edit invoice sale
    public function editInvoiceSale($id)
    {
        $this->middleware(true, true, 'edit-package', true);

        $sale_invoice = $this->db->select('SELECT * FROM sales_invoices WHERE `id` = ?', [$id])->fetch();
        if ($sale_invoice) {
            $cart_lists = $this->db->select(
                'SELECT sii.*, p.package_type AS product_package_type, p.unit_type AS product_unit_type,
                (SELECT SUM(item_total_price) FROM sale_invoice_items WHERE invoice_id = ?) as total_price 
            FROM sale_invoice_items sii
            LEFT JOIN products p ON p.id = sii.product_id
            WHERE sii.invoice_id = ?',
                [$sale_invoice['id'], $sale_invoice['id']]
            )->fetchAll();

            $seller = $this->db->select('SELECT id, user_name, phone, `address` FROM users WHERE id = ? AND `status` = ?', [$sale_invoice['seller_id'], 1])->fetch();
            if ($seller) {
                $total_debt = $this->db->select('SELECT debtor FROM customer_accounts WHERE user_id = ?', [$seller['id']])->fetch();
            }
        }

        require_once(BASE_PATH . '/resources/views/app/sales/edit-invoice/edit-invoice-sale.php');
    }






















    // edit sale product cart store
    public function editSaleProductCartStore($request, $id)
    {
        $this->middleware(true, true, 'edit-package-store', true, $request, true);

        if ($id == '' || $request['package_price_buy'] == '' || $request['package_price_sell'] == '' || $request['unit_price_buy'] == '' || $request['unit_price_sell'] == '') {
            $this->flashMessage('error', _emptyInputs);
        }

        if ($request['package_qty'] == '' && $request['unit_qty'] == '') {
            $this->flashMessage('error', 'لطفا تعداد بسته یا عدد را وارد نمائید!');
        }

        $request = $this->cleanNumbers($request, ['item_total_price', 'package_price_buy', 'package_price_sell', 'unit_price_buy']);

        $product_cart = $this->db->select('SELECT * FROM sale_invoice_items WHERE `id` = ?', [$id])->fetch();
        if (!$product_cart) {
            require_once(BASE_PATH . '/404.php');
        }

        if ($request['package_qty'] != 0) {
            $package_price = intval($request['package_qty']) * intval($request['package_price_buy']);
        }

        if ($request['unit_qty'] != 0) {
            $unit_price = intval($request['unit_qty']) * intval($request['unit_price_buy']);
        }

        if ($request['sale_discount'] != 0) {
            $item_discount =  intval($request['sale_discount']);
        }

        $request['item_total_price'] = $package_price + $unit_price - $item_discount;

        $this->db->update('sale_invoice_items', $id, array_keys($request), $request);
        $this->flashMessage('success', _success);
    }

    // delete saleproduct from cart
    public function deleteSaleProductCart($id)
    {
        $this->middleware(true, true, 'edit-package', true);

        if (!is_numeric($id)) {
            $this->flashMessage('error', 'لطفا اطلاعات درست ارسال نمائید!');
        }

        $product_cart = $this->db->select('SELECT id, product_id, quantity FROM sale_invoice_items WHERE `id` = ?', [$id])->fetch();
        if (!$product_cart) {
            require_once(BASE_PATH . '/404.php');
            exit;
        }
        $inventory = $this->db->select('SELECT id, quantity FROM inventory WHERE `product_id` = ?', [$product_cart['product_id']])->fetch();

        $newQuantity = intval($product_cart['quantity']) + intval($inventory['quantity']);

        $this->db->update('inventory', $inventory['id'], ['quantity'], [$newQuantity]);
        $this->db->delete('sale_invoice_items', $id);
        $this->flashMessage('success', _success);
        exit;
    }

    // delete sale invoice from buy product form
    public function deleteSaleInvoice($id)
    {
        $this->middleware(true, true, 'edit-package', true);

        if (!is_numeric($id)) {
            $this->flashMessage('error', 'لطفا اطلاعات درست ارسال نمائید!');
        }

        $invoice = $this->db->select('SELECT id FROM sales_invoices WHERE `id` = ?', [$id])->fetch();

        if (!$invoice) {
            require_once(BASE_PATH . '/404.php');
            exit;
        }

        $this->db->delete('sales_invoices', $id);
        $this->flashMessage('success', _success);
        exit;
    }

    // close invoice
    public function closeSaleInvoiceStore($request)
    {
        $this->middleware(true, true, 'students', true, $request, true);

        // check pain amount to not larg
        $total_price = isset($request['total_price']) && is_numeric($request['total_price']) ? floatval($request['total_price']) : 0;
        $sale_discount = isset($request['sale_discount']) && is_numeric($request['sale_discount']) ? floatval($request['sale_discount']) : 0;
        $sale_paid_amount = isset($request['sale_paid_amount']) && is_numeric($request['sale_paid_amount']) ? floatval($request['sale_paid_amount']) : 0;

        if (
            !is_numeric($request['total_price']) && $request['total_price'] !== '' ||
            !is_numeric($request['sale_discount']) && $request['sale_discount'] !== '' ||
            !is_numeric($request['sale_paid_amount']) && $request['sale_paid_amount'] !== ''
        ) {
            $this->flashMessage('error', 'لطفا فقط مقدار عددی وارد کنید!');
        } else {
            $remaining_amount = $total_price - $sale_discount - $sale_paid_amount;
            $after_discount = $sale_discount + $sale_paid_amount;

            if ($sale_paid_amount > $total_price || $remaining_amount < 0) {
                $this->flashMessage('error', 'مبلغ پرداختی نمی‌تواند بیشتر از مبلغ بِل باشد!');
            }
        }

        if ($request['seller_id'] == '') {
            if ($total_price > $after_discount) {
                $this->flashMessage('error', 'چون مشتری عمومی است، باید مبلغ کل به صورت کامل پرداخت شود!');
            }
        }

        // get sale invoice
        $invoice = $this->invoice->getSaleInvoice($request['invoice_id']);
        if (!$invoice) {
            require_once(BASE_PATH . '/404.php');
            exit();
        }

        // get year and month
        $yearMonth = $this->calendar->getYearMonth();

        // remove zeros from date
        $request['sale_invoice_date'] = intval(substr($request['sale_invoice_date'], 0, -3));

        // daily reports
        $this->financial->daily_reports([
            'total_sales' => $total_price,
            'total_payments' => $sale_paid_amount,
            'total_discounts'    => $sale_discount,
            'total_remaining'       => $remaining_amount,
        ]);

        // get sale invoice items
        $invoice_items = $this->invoice->getSaleInvoiceItems($invoice['id']);


        if (!empty(trim($request['seller_id']))) {
            $this->user->handleAccountTransaction([
                'user_id'       => $request['seller_id'],
                'ref_id' => $invoice['id'],
                'remaining'     => $remaining_amount,
                'total_price'   => $total_price,
                'paid_amount'   => $sale_paid_amount,
                'invoice_date'  => $request['sale_invoice_date'],
                'sale_discount' => $sale_discount,
                'who_it'        => $request['who_it'],
                'year'          => $yearMonth['year'],
                'month'         => $yearMonth['month'],
            ]);
        }

        // loop for sale invoice items
        foreach ($invoice_items as $item) {
            // exist product to sales table?
            $existingSaleInventory = $this->db->select("SELECT * FROM sales WHERE product_id = ? AND unit_price_buy = ? AND unit_price_sell = ? ORDER BY id DESC", [$item['product_id'], $item['unit_price_buy'], $item['unit_price_sell']])->fetch();

            if ($existingSaleInventory) {
                if (intval($existingSaleInventory['unit_price_buy']) == intval($item['unit_price_buy'])) {
                    $newQuantity = $existingSaleInventory['quantity'] + ($item['package_qty'] * $this->invoice->quantityInPackage($item['product_id'])) + $item['unit_qty'];

                    $quantityUpdate = ['quantity' => $newQuantity];
                    $this->db->update('sales', $existingSaleInventory['id'], array_keys($quantityUpdate), $quantityUpdate);
                } else {

                    $newInventory = [
                        'product_id' => $item['product_id'],
                        'product_name' => $item['product_name'],
                        'quantity' => (intval($item['package_qty']) * $this->invoice->quantityInPackage($item['product_id'])) + intval($item['unit_qty']),
                        'unit_price_sell' => $item['unit_price_sell'],
                        'unit_price_buy' => $item['unit_price_buy'],
                        'package_price_buy' => $item['package_price_buy'],
                        'package_price_sell' => $item['package_price_sell'],
                        'quantity_in_pack' => $item['quantity_in_pack'],
                    ];
                    $this->db->insert('inventory', array_keys($newInventory), $newInventory);
                }
            } else {
                $newSaleInventory = [
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'quantity' => (intval($item['package_qty']) * $this->invoice->quantityInPackage($item['product_id'])) + intval($item['unit_qty']),
                    'unit_price_sell' => $item['unit_price_sell'],
                    'unit_price_buy' => $item['unit_price_buy'],
                    'package_price_buy' => $item['package_price_buy'],
                    'package_price_sell' => $item['package_price_sell'],
                    'quantity_in_pack' => $item['quantity_in_pack'],
                ];

                $this->db->insert('sales', array_keys($newSaleInventory), $newSaleInventory);
            }
        } //end foreach

        $invoice_infos = [
            'sale_total_amount' => $total_price,
            'sale_discount' => $sale_discount,
            'seller_id' => $request['seller_id'],
            'sale_invoice_date' => $request['sale_invoice_date'],
            'sale_paid_amount' => $sale_paid_amount,
            'remaining_amount' => $remaining_amount,
            'sale_inv_description' => $request['sale_inv_description'],
            'sale_status' => 2,
        ];
        $inserted = $this->db->update('sales_invoices', $invoice['id'], array_keys($invoice_infos), $invoice_infos);
        if ($inserted) {
            if (isset($request['invoice_print'])) {
                $this->redirect('sale-invoice-print/' . $request['invoice_id']);
            }
        }
        $this->flashMessage('success', _success);
    }
}
