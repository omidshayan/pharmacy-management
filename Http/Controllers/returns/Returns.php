<?php

namespace App;

require_once 'Http/Models/Calendar.php';
require_once 'Http/Models/Invoice.php';
require_once 'Http/Models/Notification.php';
require_once 'Http/Models/Transaction.php';
require_once 'Http/Models/Reports.php';

use Models\Calendar\Calendar;
use Models\Invoice\Invoice;
use Models\Notification\Notification;
use Models\Transaction\Transaction;
use Models\Reports\Reports;

class Returns extends App
{
    private $calendar;
    private $invoice;
    private $notification;
    private $transaction;
    private $reports;

    public function __construct()
    {
        parent::__construct();
        $this->calendar = new Calendar();
        $this->invoice = new Invoice();
        $this->notification = new Notification();
        $this->transaction = new Transaction();
        $this->reports = new Reports();
    }

    /////////////// return from slaes //////////////

    // add return form sale page
    public function returnFromSale()
    {
        $this->middleware(true, true, 'general', true);

        $branchId = $this->getBranchId();

        // get invoice
        $return = $this->db->select('SELECT * FROM invoices WHERE branch_id = ? AND invoice_type = ? AND `status` = ?', [$branchId, 3, 1])->fetch();
        $cash_boxes = $this->db->select(
            'SELECT id, `name` FROM cash_boxes WHERE `status` = ? AND branch_id = ?',
            [1, $branchId]
        )->fetchAll();

        if ($return) {
            $cart_lists = $this->db->select(
                'SELECT sii.*, p.package_type AS product_package_type, p.unit_type AS product_unit_type,
                (SELECT SUM(item_total_price) FROM invoice_items WHERE invoice_id = ?) as total_price 
            FROM invoice_items sii
            LEFT JOIN products p ON p.id = sii.product_id
            WHERE sii.invoice_id = ?',
                [$return['id'], $return['id']]
            )->fetchAll();

            $seller = $this->db->select('SELECT id, user_name, phone, `address` FROM users WHERE id = ? AND `status` = ?', [$return['user_id'], 1])->fetch();

            if ($seller) {
                // $total_debt = $this->db->select('SELECT debtor FROM customer_accounts WHERE user_id = ?', [$seller['id']])->fetch();
            }
        }

        require_once(BASE_PATH . '/resources/views/app/returns/return-from-sale/return-from-sale.php');
    }

    // get invoice items ajax
    public function getReturnInvoiceItemsAjax()
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();

        $invoice = $this->db->select('SELECT id FROM invoices WHERE invoice_type = ? AND `status` = ? AND branch_id = ?', [3, 1, $branchId])->fetch();

        if (!$invoice) {
            $this->send_json_response(true, 'empty', [
                'items' => []
            ]);
            exit();
        }

        $items = $this->invoice->getInvoiceItemsInventory($invoice['id']);

        $this->send_json_response(true, 'ok', [
            'items' => $items,
            'invoice_id' => $invoice['id']
        ]);

        exit();
    }

    // return from buy store
    public function returnSaleStore($request)
    {
        // NOTE check types and existing inventory 
        $this->middleware(true, true, 'general');

        $required = ['product_id', 'product_name', 'package_price_sell'];
        foreach ($required as $field) {
            if (empty($request[$field])) {
                $this->send_json_response(false, "اطلاعات ارسال شده مشکل دارد!");
                exit();
            }
        }

        // validations
        $this->validateInputs($request);

        $userInfo = $this->currentUser();

        // removing extra zeros
        $request = $this->cleanNumbers($request, ['item_total_price', 'package_price_buy', 'package_price_sell', 'quantity']);

        // clean
        $request = $this->cleanNumericFields($request, [
            'unit_qty',
            'package_qty',
            'package_price_buy',
            'unit_price_buy',
            'item_total_price'
        ]);

        $type = 3;

        $branchId = $this->getBranchId();

        // create invoice array
        $return_invoice = [
            'invoice_type' => $type, // return form sale
            'branch_id' => $branchId,
            'user_id' => !empty($request['user_id']) ? $request['user_id'] : 1,
            'who_it' => $userInfo['name'],
        ];

        // check invoic - new create or update ivoice
        $invoice_id = $this->invoice->InvoiceConfirm($return_invoice);


        // check product unit type 
        $unit_type = $this->db->select('SELECT unit_type FROM products WHERE id = ? AND branch_id = ?', [$request['main_p_id'], $branchId])->fetch();


        if (!is_null($unit_type['unit_type'])) {

            $unit_qty    = 1;
            $package_qty = 0;

            $quantity = 1;

            $item_total_price = $request['unit_price_sell'];
        } else {

            $unit_qty    = 0;
            $package_qty = 1;

            $quantity = 1;

            $item_total_price = $request['package_price_sell'];
        }

        $invoice_items = [
            'branch_id' => $branchId,
            'invoice_id' => $invoice_id,
            'product_id' => $request['main_p_id'],
            'product_name' => $request['product_name'],
            'unit_qty'          => $unit_qty,
            'package_qty'       => $package_qty,
            'quantity_in_pack'  => $request['quantity_in_pack'],
            'package_price_buy' => $request['package_price_buy'],
            'package_price_sell' => $request['package_price_sell'],
            'unit_price_buy' => $request['unit_price_buy'],
            'unit_price_sell' => $request['unit_price_sell'],
            'item_total_price'  => $item_total_price,
            'quantity'          => $quantity,
        ];

        //  Check if product exists in this invoice
        $exist_product = $this->invoice->getInvoiceItem($invoice_id, $request['main_p_id']);

        if (!$exist_product) {
            //  Insert new product
            $this->db->insert('invoice_items', array_keys($invoice_items), $invoice_items);
        } else {
            $update_data = [
                'quantity' => $exist_product['quantity'] + $invoice_items['quantity'],
                'package_qty' => $exist_product['package_qty'] + $invoice_items['package_qty'],
                'unit_qty' => $exist_product['unit_qty'] + $invoice_items['unit_qty'],
                'item_total_price' => $exist_product['item_total_price'] + $invoice_items['item_total_price'],
                'package_price_sell' => $invoice_items['package_price_sell'],
                'package_price_buy' => $invoice_items['package_price_buy'],
                'unit_price_sell' => $invoice_items['unit_price_sell'],
                'unit_price_buy' => $invoice_items['unit_price_buy'],
            ];

            $this->db->update('invoice_items', $exist_product['id'], array_keys($update_data), $update_data);
        }


        $invoice_items = $this->invoice->getInvoiceItems($invoice_id);

        $this->send_json_response(true, _added, [
            'items' => $invoice_items,
            'invoice_id' => $invoice_id,
        ]);
        exit;
    }

    // close invoice 
    // public function closeReturnSaleInvoice($request)
    // {
    //     $this->middleware(true, true, 'general', true, $request, true);

    //     // check inputs number
    //     $this->normalizeFloatFields($request, 'total_price', 'paid_amount', 'discount');

    //     // check total price
    //     if ($request['paid_amount'] > $request['total_price']) {
    //         $this->flashMessage('error', 'مبلغ پرداختی نمی تواند بیشتر از مبلغ بِل باشد!');
    //         return;
    //     }

    //     // get invoice and check existing
    //     $invoice = $this->invoice->getInvoice($request['invoice_id'], $request['branch_id']);
    //     if (!$invoice) {
    //         throw new \Exception('Invoice not found');
    //     }

    //     // date and validations
    //     // $request['buy_date'] = intval(substr($request['buy_date'], 0, -3));
    //     $request = $this->validateInputs($request);

    //     // check current balances
    //     $inventory = $this->db->select('SELECT * FROM settings')->fetch();
    //     $account = $this->db->select('SELECT * FROM financial_summary')->fetch();
    //     if ($request['paid_amount'] != 0 && $inventory['buy_any_situation'] == 2) {
    //         if ((float)$request['paid_amount'] > (float)$account['current_balance']) {
    //             $this->flashMessage('error', 'مبلغ قابل پرداخت، بیشتر از موجودی صندوق است!');
    //             return;
    //         }
    //     }

    //     // get month and year
    //     $yearMonth = $this->calendar->getYearMonth();

    //     // get invoice items
    //     $invoice_items = $this->invoice->getInvoiceItems($invoice['id']);
    //     // check invoice items
    //     if (!$invoice_items) {
    //         $this->flashMessage('error', 'بِل مورد نظر خالی است!');
    //         return;
    //     }

    //     try {
    //         $this->db->beginTransaction();

    //         // foreach for items
    //         foreach ($invoice_items as $item) {

    //             $existingInventory = $this->db->select(
    //                 "SELECT * FROM inventory WHERE product_id = ? AND branch_id = ?",
    //                 [$item['product_id'], $item['branch_id']]
    //             )->fetch();

    //             if ($existingInventory) {

    //                 $newQuantity = $existingInventory['quantity']
    //                     + ($item['package_qty'] * $item['quantity_in_pack'])
    //                     + $item['unit_qty'];

    //                 $new_inventory = [
    //                     'quantity' => $newQuantity,
    //                     'package_price_buy' => $item['package_price_buy'],
    //                     'package_price_sell' => $item['package_price_sell'],
    //                 ];

    //                 $this->db->update('inventory', $existingInventory['id'], array_keys($new_inventory), $new_inventory);

    //                 $batches = [
    //                     'branch_id' => $item['branch_id'],
    //                     'product_id' => $item['product_id'],
    //                     'package_price_buy' => $item['package_price_buy'],
    //                     'package_price_sell' => $item['package_price_sell'],
    //                     'quantity' => intval($item['quantity']),
    //                     'expiration_date' => $item['expiration_date'],
    //                 ];

    //                 $this->db->insert('product_batches', array_keys($batches), $batches);
    //             } else {
    //                 $newInventory = [
    //                     'branch_id' => $item['branch_id'],
    //                     'product_id' => $item['product_id'],
    //                     'product_name' => $item['product_name'],
    //                     'quantity' => intval($item['quantity']),
    //                     'package_price_buy' => $item['package_price_buy'],
    //                     'package_price_sell' => $item['package_price_sell'],
    //                     'quantity_in_pack' => $item['quantity_in_pack'],
    //                     'inventory_year' => $yearMonth['year'],
    //                     'inventory_month' => $yearMonth['month'],
    //                 ];

    //                 $batches = [
    //                     'branch_id' => $item['branch_id'],
    //                     'product_id' => $item['product_id'],
    //                     'package_price_buy' => $item['package_price_buy'],
    //                     'package_price_sell' => $item['package_price_sell'],
    //                     'quantity' => intval($item['quantity']),
    //                     'expiration_date' => $item['expiration_date'],
    //                 ];

    //                 $this->db->insert('inventory', array_keys($newInventory), $newInventory);
    //                 $this->db->insert('product_batches', array_keys($batches), $batches);
    //             }
    //         }
    //         // end foreach

    //         $type = 3;

    //         // array for transaction
    //         $transaction_data = [
    //             'branch_id' => $request['branch_id'],
    //             'user_id' => $request['seller_id'],
    //             'ref_id' => $invoice['invoice_number'],
    //             'total_price' =>  $request['total_price'],
    //             'paid_amount' => $request['paid_amount'],
    //             'discount' => $request['discount'],
    //             'transaction_date'  => $request['buy_date'],
    //             'who_it' => $request['who_it'],
    //             'year' => $yearMonth['year'],
    //             'month' => $yearMonth['month'],
    //             'transaction_type' => $type, // return from sale
    //         ];
    //         $this->transaction->addNewTransaction($transaction_data);


    //         // send notificatons
    //         $notif_data = [
    //             'branch_id' => $request['branch_id'],
    //             'user_id' => $request['seller_id'],
    //             'ref_id' => $invoice['id'],
    //             'type' => $type,
    //         ];
    //         $this->notification->sendNotif($notif_data);


    //         // update account balance
    //         $accoutBalance = [
    //             'branch_id' => $request['branch_id'],
    //             'user_id' => $request['seller_id'],
    //             'total_price' =>  $request['total_price'],
    //             'paid_amount' => $request['paid_amount'],
    //             'year' => $yearMonth['year'],
    //             'type' => $type,
    //         ];
    //         $this->transaction->updateAccountBalance($accoutBalance);


    //         // update daily reports
    //         $dailyReports = [
    //             'branch_id' => $request['branch_id'],
    //             'total_price' =>  $request['total_price'],
    //             'paid_amount' => $request['paid_amount'],
    //             'type' => $type,
    //         ];
    //         $this->reports->updateDailyReports($dailyReports);


    //         // update fund
    //         $paid = isset($request['paid_amount']) ? (float)$request['paid_amount'] : 0;

    //         if ($paid > 0) {
    //             $updateFund = [
    //                 'branch_id'   => (int)$request['branch_id'],
    //                 'paid_amount' =>  $request['paid_amount'],
    //                 'type'        => $type,
    //                 'source'      => isset($request['source']) ? (int)$request['source'] : 1,
    //             ];

    //             $this->reports->updateFund($updateFund);
    //         }

    //         // invoice information for closeed
    //         $invoice_infos = [
    //             'total_amount' => $request['total_price'],
    //             'discount' => $request['discount'],
    //             'user_id' => $request['seller_id'],
    //             'date' => $request['buy_date'],
    //             'paid_amount' => $request['paid_amount'],
    //             'year' => $yearMonth['year'],
    //             'month' => $yearMonth['month'],
    //             'description' => $request['description'],
    //             'status' => 2,
    //         ];


    //         $this->db->update('invoices', $invoice['id'], array_keys($invoice_infos), $invoice_infos);

    //         $this->db->commit();

    //         $this->flashMessage('success', _success);
    //     } catch (\Exception $e) {
    //         $this->db->rollBack();

    //         $this->flashMessage('error', 'خطا در ثبت بِل: ' . $e->getMessage());
    //     }
    // }
    public function closeReturnSaleStore($request)
    {
        $this->middleware(true, true, 'general');
        $this->normalizeFloatFields($request, 'total_price', 'paid_amount', 'discount');

        $total_price     = (float)$request['total_price'];
        $return_discount = (float)$request['discount'];
        $return_paid_amount = (float)$request['paid_amount'];
        $netRemaining    = $total_price - $return_discount - $return_paid_amount;

        if ($return_paid_amount > ($total_price - $return_discount)) {
            $this->flashMessage('error', 'مبلغ پرداختی نمی‌تواند بیشتر از مبلغ بِل باشد!');
            return;
        }

        $customerId = !empty($request['user_id']) ? (int)$request['user_id'] : (int)$this->customerId();

        if ($customerId == 1 && $total_price > $return_paid_amount + $return_discount) {
            $this->flashMessage('error', 'مشتری عمومی باید تسویه شود!');
            return;
        }

        $branchId = (int)$this->getBranchId();
        $userInfo = $this->currentUser();
        $invoice  = $this->invoice->getInvoice($request['invoice_id'], $branchId);

        if (!$invoice) throw new \Exception('Invoice not found');

        $yearMonth = $this->calendar->getYearMonth();
        $invoice_items = $this->invoice->getInvoiceItems($invoice['id']);

        $storeWarehouse = $this->db->select("SELECT id FROM warehouses WHERE branch_id = ? AND type = 'shop' LIMIT 1", [$branchId])->fetch();

        $settings = $this->db->select('SELECT buy_any_situation FROM settings LIMIT 1')->fetch();
        $fundAccount = $this->db->select('SELECT current_balance FROM financial_summary LIMIT 1')->fetch();

        if ($return_paid_amount > 0 && $settings['buy_any_situation'] == 2) {
            if ($return_paid_amount > (float)$fundAccount['current_balance']) {
                $this->flashMessage('error', 'موجودی صندوق برای مرجوعی کافی نیست!');
                return;
            }
        }

        try {
            $this->db->beginTransaction();

            foreach ($invoice_items as $item) {

                $finalWarehouseId = $item['warehouse_id'] ?? $request['warehouse_id'] ?? (int)$storeWarehouse['id'];
                $returnQty = ((float)$item['package_qty'] * (float)$item['quantity_in_pack']) + (float)$item['unit_qty'];

                $movementData = [
                    'branch_id'          => $branchId,
                    'product_id'         => $item['product_id'],
                    'invoice_id'         => $invoice['id'],
                    'invoice_item_id'    => $item['id'],
                    'movement_type'      => 2,
                    'reference_type'     => 3,
                    'total_unit_qty'     => $returnQty,
                    'remaining_qty'      => $returnQty,
                    'package_price_buy'  => $item['package_price_buy'],
                    'package_price_sell' => $item['package_price_sell'],
                    'unit_price_buy'     => $item['unit_price_buy'],
                    'unit_price_sell'    => $item['unit_price_sell'],
                    'movement_date'      => $request['date'],
                    'warehouse_id'       => $finalWarehouseId,
                    'expiration_date'    => $item['expiration_date'] ?: null,
                    'who_it'             => $userInfo['name'],
                ];
                $this->db->insert('inventory_movements', array_keys($movementData), $movementData);

                $existingInventory = $this->db->select("SELECT id, quantity FROM inventory WHERE product_id = ? AND branch_id = ? AND warehouse_id = ? LIMIT 1", [$item['product_id'], $branchId, $finalWarehouseId])->fetch();

                if ($existingInventory) {
                    $newQty = (float)$existingInventory['quantity'] + $returnQty;
                    $this->db->update('inventory', $existingInventory['id'], ['quantity'], [$newQty]);
                } else {
                    $insertData = [
                        'branch_id'          => $branchId,
                        'product_id'         => $item['product_id'],
                        'product_name'       => $item['product_name'],
                        'quantity'           => $returnQty,
                        'warehouse_id'       => $finalWarehouseId,
                        'package_price_buy'  => $item['package_price_buy'],
                        'package_price_sell' => $item['package_price_sell'],
                        'unit_price_buy'     => $item['unit_price_buy'],
                        'unit_price_sell'    => $item['unit_price_sell'],
                    ];
                    $this->db->insert('inventory', array_keys($insertData), array_values($insertData));
                }
            }

            $this->transaction->addNewTransaction([
                'branch_id'        => $branchId,
                'ref_id'           => $invoice['id'],
                'user_id'          => $customerId,
                'transaction_type' => 3, // برگشت از فروش
                'total_amount'     => $total_price,
                'discount'         => $return_discount,
                'paid_amount'      => $return_paid_amount,
                'transaction_date' => $request['date'],
                'description'      => $request['description'] ?? 'برگشت از فروش بِل شماره ' . $invoice['id'],
                'status'           => 1,
                'who_it'           => $userInfo['name'],
                'balance'          => $netRemaining,
            ]);

            $this->notification->sendNotif(['branch_id' => $branchId, 'user_id' => $customerId, 'ref_id' => $invoice['id'], 'type' => 3]);

            if ($return_paid_amount > 0) {
                $this->reports->updateFund([
                    'branch_id'  => $branchId,
                    'to_cash_id' => $request['source'],
                    'amount'     => $return_paid_amount,
                    'ref_id'     => $invoice['id'],
                    'type'       => 3,
                    'user_id'    => $customerId,
                    'date'       => $request['date'],
                    'source'     => $request['source'],
                ]);
            }

            $imageName = $this->handleImageUpload($request['image'], 'images/invoices');

            $invoice_infos = [
                'total_amount' => $total_price,
                'discount'     => $return_discount,
                'user_id'      => $customerId,
                'date'         => $request['date'],
                'paid_amount'  => $return_paid_amount,
                'year'         => $yearMonth['year'],
                'month'        => $yearMonth['month'],
                'status'       => 2,
                'image'        => $imageName
            ];
            $inserted = $this->db->update('invoices', $invoice['id'], array_keys($invoice_infos), $invoice_infos);

            $this->db->commit();

            if ($inserted && isset($request['invoice_print'])) {
                $this->flashMessageId('success', 'بِل برگشتی با موفقیت ثبت شد', $request['invoice_id']);
                return;
            }

            $this->flashMessage('success', 'بِل برگشت از فروش با موفقیت ثبت شد.');
        } catch (\Exception $e) {
            $this->db->rollBack();
            $this->flashMessage('error', 'خطا: ' . $e->getMessage());
        }
    }

    // show returns Sales
    public function returnSales()
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();

        $returns = $this->db->select('SELECT * FROM invoices WHERE branch_id = ? AND invoice_type = ?', [$branchId, 3])->fetchAll();
        require_once(BASE_PATH . '/resources/views/app/returns/returns.php');
    }

    // return sale details
    public function returnSaleDetails($id)
    {
        $this->middleware(true, true, 'general');
        $type = 3;
        $invoice = $this->invoice->getInvoiceDetails($id, $type);
        $invoice_items = $this->invoice->getInvoiceItems($invoice['id']);
        require_once(BASE_PATH . '/resources/views/app/returns/return-from-sale/return-sale-invoice-details.php');
        exit();
    }


    ////////////////////// purchase ////////////////////////
    // return form buy
    public function returnFromBuy()
    {
        $this->middleware(true, true, 'general', true);

        $branchId = $this->getBranchId();

        // get invoice
        $return = $this->db->select('SELECT * FROM invoices WHERE branch_id = ? AND invoice_type = ? AND `status` = ?', [$branchId, 4, 1])->fetch();
        $cash_boxes = $this->db->select(
            'SELECT id, `name` FROM cash_boxes WHERE `status` = ? AND branch_id = ?',
            [1, $branchId]
        )->fetchAll();

        if ($return) {
            $cart_lists = $this->db->select(
                'SELECT sii.*, p.package_type AS product_package_type, p.unit_type AS product_unit_type,
                (SELECT SUM(item_total_price) FROM invoice_items WHERE invoice_id = ?) as total_price 
            FROM invoice_items sii
            LEFT JOIN products p ON p.id = sii.product_id
            WHERE sii.invoice_id = ?',
                [$return['id'], $return['id']]
            )->fetchAll();

            $seller = $this->db->select('SELECT id, user_name, phone, `address` FROM users WHERE id = ? AND `status` = ?', [$return['user_id'], 1])->fetch();

            if ($seller) {
                // $total_debt = $this->db->select('SELECT debtor FROM customer_accounts WHERE user_id = ?', [$seller['id']])->fetch();
            }
        }
        require_once(BASE_PATH . '/resources/views/app/returns/return-from-buy/return-from-buy.php');
    }

    // get invoice items ajax
    public function getReturnBuyInvoiceItemsAjax()
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();

        $invoice = $this->db->select('SELECT id FROM invoices WHERE invoice_type = ? AND `status` = ? AND branch_id = ?', [4, 1, $branchId])->fetch();

        if (!$invoice) {
            $this->send_json_response(true, 'empty', [
                'items' => []
            ]);
            exit();
        }

        $items = $this->invoice->getInvoiceItemsInventory($invoice['id']);

        $this->send_json_response(true, 'ok', [
            'items' => $items,
            'invoice_id' => $invoice['id']
        ]);

        exit();
    }

    // return from buy store
    public function returnBuyStore($request)
    {
        // NOTE check types and existing inventory 
        $this->middleware(true, true, 'general');

        $required = ['product_id', 'product_name', 'package_price_sell'];
        foreach ($required as $field) {
            if (empty($request[$field])) {
                $this->send_json_response(false, "اطلاعات ارسال شده مشکل دارد!");
                exit();
            }
        }

        // validations
        $this->validateInputs($request);

        $userInfo = $this->currentUser();

        // removing extra zeros
        $request = $this->cleanNumbers($request, ['item_total_price', 'package_price_buy', 'package_price_sell', 'quantity']);

        // clean
        $request = $this->cleanNumericFields($request, [
            'unit_qty',
            'package_qty',
            'package_price_buy',
            'unit_price_buy',
            'item_total_price'
        ]);

        $type = 4;

        $branchId = $this->getBranchId();

        // create invoice array
        $return_invoice = [
            'invoice_type' => $type, // return form sale
            'branch_id' => $branchId,
            'user_id' => !empty($request['user_id']) ? $request['user_id'] : 1,
            'who_it' => $userInfo['name'],
        ];

        // check invoic - new create or update ivoice
        $invoice_id = $this->invoice->InvoiceConfirm($return_invoice);


        // check product unit type 
        $unit_type = $this->db->select('SELECT unit_type FROM products WHERE id = ? AND branch_id = ?', [$request['main_p_id'], $branchId])->fetch();


        if (!is_null($unit_type['unit_type'])) {

            $unit_qty    = 1;
            $package_qty = 0;

            $quantity = 1;

            $item_total_price = $request['unit_price_sell'];
        } else {

            $unit_qty    = 0;
            $package_qty = 1;

            $quantity = 1;

            $item_total_price = $request['package_price_sell'];
        }

        $invoice_items = [
            'branch_id' => $branchId,
            'invoice_id' => $invoice_id,
            'product_id' => $request['main_p_id'],
            'product_name' => $request['product_name'],
            'unit_qty'          => $unit_qty,
            'package_qty'       => $package_qty,
            'quantity_in_pack'  => $request['quantity_in_pack'],
            'package_price_buy' => $request['package_price_buy'],
            'package_price_sell' => $request['package_price_sell'],
            'unit_price_buy' => $request['unit_price_buy'],
            'unit_price_sell' => $request['unit_price_sell'],
            'item_total_price'  => $item_total_price,
            'quantity'          => $quantity,
        ];

        //  Check if product exists in this invoice
        $exist_product = $this->invoice->getInvoiceItem($invoice_id, $request['main_p_id']);

        if (!$exist_product) {
            //  Insert new product
            $this->db->insert('invoice_items', array_keys($invoice_items), $invoice_items);
        } else {
            $update_data = [
                'quantity' => $exist_product['quantity'] + $invoice_items['quantity'],
                'package_qty' => $exist_product['package_qty'] + $invoice_items['package_qty'],
                'unit_qty' => $exist_product['unit_qty'] + $invoice_items['unit_qty'],
                'item_total_price' => $exist_product['item_total_price'] + $invoice_items['item_total_price'],
                'package_price_sell' => $invoice_items['package_price_sell'],
                'package_price_buy' => $invoice_items['package_price_buy'],
                'unit_price_sell' => $invoice_items['unit_price_sell'],
                'unit_price_buy' => $invoice_items['unit_price_buy'],
            ];

            $this->db->update('invoice_items', $exist_product['id'], array_keys($update_data), $update_data);
        }


        $invoice_items = $this->invoice->getInvoiceItems($invoice_id);

        $this->send_json_response(true, _added, [
            'items' => $invoice_items,
            'invoice_id' => $invoice_id,
        ]);
        exit;
    }

    // close invoice return buy 
    public function closeReturnBuyStore($request)
    {
        $this->middleware(true, true, 'general');
        $this->normalizeFloatFields($request, 'total_price', 'paid_amount', 'discount');

        $total_price        = (float)$request['total_price'];
        $return_discount    = (float)$request['discount'];
        $return_paid_amount = (float)$request['paid_amount']; // مبلغی که فروشنده به ما پس داده
        $netRemaining       = $total_price - $return_discount - $return_paid_amount;

        if ($return_paid_amount > ($total_price - $return_discount)) {
            $this->flashMessage('error', 'مبلغ دریافتی نمی‌تواند بیشتر از مبلغ بِل باشد!');
            return;
        }

        $branchId = (int)$this->getBranchId();
        $userInfo = $this->currentUser();
        $invoice  = $this->invoice->getInvoice($request['invoice_id'], $branchId);

        if (!$invoice) throw new \Exception('Invoice not found');

        $customerId = !empty($request['seller_id']) ? (int)$request['seller_id'] : (int)$this->customerId();

        $yearMonth = $this->calendar->getYearMonth();
        $invoice_items = $this->invoice->getInvoiceItems($invoice['id']);

        if (!$invoice_items) {
            $this->flashMessage('error', 'بِل خالی است!');
            return;
        }

        try {
            $this->db->beginTransaction();

            foreach ($invoice_items as $item) {
                $returnQtyTotal = ((float)$item['package_qty'] * (float)$item['quantity_in_pack']) + (float)$item['unit_qty'];

                if ($returnQtyTotal <= 0) continue;

                // ۱. بررسی موجودی کل
                $existingInventory = $this->db->select(
                    "SELECT id, quantity FROM inventory WHERE product_id = ? AND branch_id = ? LIMIT 1",
                    [$item['product_id'], $branchId]
                )->fetch();

                if (!$existingInventory || (float)$existingInventory['quantity'] < $returnQtyTotal) {
                    throw new \Exception("موجودی دوا {$item['product_name']} برای مرجوعی کافی نیست!");
                }

                // ۲. کسر از FIFO (پیدا کردن بچ‌های ورودی برای کسر remaining_qty)
                $fifo_movements = $this->db->select(
                    "SELECT * FROM inventory_movements 
                     WHERE product_id = ? AND branch_id = ? AND movement_type = 2 AND remaining_qty > 0 
                     ORDER BY movement_date ASC, id ASC",
                    [$item['product_id'], $branchId]
                )->fetchAll();

                $tempReturnQty = $returnQtyTotal;

                foreach ($fifo_movements as $movement) {
                    if ($tempReturnQty <= 0) break;

                    $availableInThisBatch = (float)$movement['remaining_qty'];
                    $takeQty = min($tempReturnQty, $availableInThisBatch);

                    $newRemaining = $availableInThisBatch - $takeQty;
                    $this->db->update('inventory_movements', $movement['id'], ['remaining_qty'], [$newRemaining]);

                    // ۳. ثبت رکورد خروج (برگشت از خرید) در Movements
                    $movementData = [
                        'branch_id'          => $branchId,
                        'product_id'         => $item['product_id'],
                        'invoice_id'         => $invoice['id'],
                        'invoice_item_id'    => $item['id'],
                        'movement_type'      => 1, // خروج
                        'reference_type'     => 4, // برگشت از خرید
                        'total_unit_qty'     => $takeQty,
                        'remaining_qty'      => 0,
                        'package_price_buy'  => $item['package_price_buy'],
                        'package_price_sell' => $item['package_price_sell'],
                        'unit_price_buy'     => $item['unit_price_buy'],
                        'unit_price_sell'    => $item['unit_price_sell'],
                        'movement_date'      => $request['date'],
                        'warehouse_id'       => $movement['warehouse_id'],
                        'expiration_date'    => $item['expiration_date'] ?: null,
                        'who_it'             => $userInfo['name'],
                    ];
                    $this->db->insert('inventory_movements', array_keys($movementData), $movementData);

                    $tempReturnQty -= $takeQty;
                }

                // اگر هنوز مقداری مانده باشد (مرجوعی بیش از رکوردهای ورودی موجود)
                if ($tempReturnQty > 0) {
                    throw new \Exception("خطا در سیستم FIFO: مقدار مرجوعی {$item['product_name']} با بچ‌های ورودی همخوانی ندارد.");
                }

                // ۴. آپدیت موجودی کل در Inventory
                $newInventoryTotal = (float)$existingInventory['quantity'] - $returnQtyTotal;
                $this->db->update('inventory', $existingInventory['id'], ['quantity'], [$newInventoryTotal]);
            }

            // ۵. ثبت تراکنش مالی (نوع ۴)
            $this->transaction->addNewTransaction([
                'branch_id'        => $branchId,
                'ref_id'           => $invoice['id'],
                'user_id'          => $customerId,
                'transaction_type' => 4, // برگشت از خرید
                'total_amount'     => $total_price,
                'discount'         => $return_discount,
                'paid_amount'      => $return_paid_amount,
                'transaction_date' => $request['date'],
                'description'      => $request['description'] ?? 'برگشت از خرید بِل شماره ' . $invoice['id'],
                'status'           => 1,
                'who_it'           => $userInfo['name'],
                'balance'          => $netRemaining,
            ]);

            $this->notification->sendNotif(['branch_id' => $branchId, 'user_id' => $customerId, 'ref_id' => $invoice['id'], 'type' => 4]);

            // ۶. آپدیت صندوق (پولی که به حساب ما برگشته)
            if ($return_paid_amount > 0) {
                $this->reports->updateFund([
                    'branch_id'  => $branchId,
                    'to_cash_id' => $request['source'],
                    'amount'     => $return_paid_amount,
                    'ref_id'     => $invoice['id'],
                    'type'       => 4,
                    'user_id'    => $customerId,
                    'date'       => $request['date'],
                    'source'     => $request['source'],
                ]);
            }

            $imageName = $this->handleImageUpload($request['image'], 'images/invoices');

            $invoice_infos = [
                'total_amount' => $total_price,
                'discount'     => $return_discount,
                'user_id'      => $customerId,
                'date'         => $request['date'],
                'paid_amount'  => $return_paid_amount,
                'year'         => $yearMonth['year'],
                'month'        => $yearMonth['month'],
                'status'       => 2,
                'image'        => $imageName,
                'description'  => $request['description'] ?? null
            ];
            $this->db->update('invoices', $invoice['id'], array_keys($invoice_infos), $invoice_infos);

            $this->db->commit();
            $this->flashMessage('success', 'بِل برگشت از خرید با موفقیت ثبت شد.');
        } catch (\Exception $e) {
            $this->db->rollBack();
            $this->flashMessage('error', 'خطا در ثبت برگشت از خرید: ' . $e->getMessage());
        }
    }

    // show returns buy
    public function returnBuy()
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();

        $returns = $this->db->select('SELECT * FROM invoices WHERE branch_id = ? AND invoice_type = ?', [$branchId, 4])->fetchAll();
        require_once(BASE_PATH . '/resources/views/app/returns/returns.php');
    }

    public function returnBuyDetails($id)
    {
        $this->middleware(true, true, 'general');
        $type = 3;
        $invoice = $this->invoice->getInvoiceDetails($id, $type);
        $invoice_items = $this->invoice->getInvoiceItems($invoice['id']);
        require_once(BASE_PATH . '/resources/views/app/returns/return-from-buy/return-buy-invoice-details.php');
        exit();
    }


    ////////////////////////// general /////////////////////////
    // search product
    public function returnSearchProdut($request)
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();

        $product = $this->db->select("SELECT id, product_name FROM products WHERE `status` = 1 AND product_name LIKE ? AND branch_id = ? 
            ORDER BY product_name LIMIT 20", ['%' . strtolower($request['customer_name']) . '%', $branchId])->fetchAll();

        $response = [
            'status' => 'success',
            'products' => $product,
            'message' => 'lists'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    // show returns
    public function returns()
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();

        $returns = $this->db->select('SELECT * FROM invoices WHERE branch_id = ? AND invoice_type IN (?, ?) ORDER BY id DESC', [$branchId, 3, 4])->fetchAll();
        require_once(BASE_PATH . '/resources/views/app/returns/returns.php');
    }

    // get Product Infos Return
    public function getProductInfosReturn($request)
    {
        $this->middleware(true, true, 'general');
        $productInfos = $this->db->select('SELECT * FROM products WHERE id LIKE ?', ['%' . $request['id'] . '%'])->fetch();
        $inventory = $this->db->select('SELECT * FROM inventory WHERE product_id = ?', [$request['id']])->fetch();
        $response = [
            'status' => 'success',
            'product' => $productInfos,
            'inventory' => $inventory,
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    // delete product return cart
    public function returnDeleteCart($id)
    {
        $this->middleware(true, true, 'general', true);

        if (!is_numeric($id)) {
            $this->flashMessage('error', 'لطفا اطلاعات درست ارسال نمائید!');
        }

        $branchId = $this->getBranchId();

        $product_cart = $this->db->select('SELECT id FROM invoice_items WHERE `id` = ? AND branch_id = ?', [$id, $branchId])->fetch();

        if (!$product_cart) {
            require_once(BASE_PATH . '/404.php');
            exit;
        }

        $this->db->delete('invoice_items', $id);
        $this->flashMessage('success', _success);
    }

    // delete invoice return from buy product form
    public function deleteReturnInvoice($id)
    {
        $this->middleware(true, true, 'general', true);

        if (!is_numeric($id)) {
            $this->flashMessage('error', 'لطفا اطلاعات درست ارسال نمائید!');
        }

        $invoice = $this->db->select('SELECT id FROM invoices WHERE `id` = ?', [$id])->fetch();

        if (!$invoice) {
            require_once(BASE_PATH . '/404.php');
            exit;
        }

        $this->db->delete('invoices', $id);
        $this->flashMessage('success', _success);
        exit;
    }
}
