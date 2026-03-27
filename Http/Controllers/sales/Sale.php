<?php

namespace App;

require_once 'Http/Controllers/App.php';
require_once 'Http/Models/Calendar.php';
require_once 'Http/Models/Invoice.php';
require_once 'Http/Models/Transaction.php';
require_once 'Http/Models/Notification.php';
require_once 'Http/Models/Reports.php';

use Models\Invoice\Invoice;
use Models\Transaction\Transaction;
use Models\Notification\Notification;
use Models\Calendar\Calendar;
use Models\Reports\Reports;

class Sale extends App
{
    private $invoice;
    private $transaction;
    private $notification;
    private $reports;
    private $calendar;
    public function __construct()
    {
        parent::__construct();
        $this->invoice = new Invoice();
        $this->transaction = new Transaction();
        $this->notification = new Notification();
        $this->calendar = new Calendar();
        $this->reports = new Reports();
    }

    // invoices page
    public function addSale()
    {
        $this->middleware(true, true, 'general', true);

        $branchId = $this->getBranchId();
        $sale_invoice = $this->db->select('SELECT * FROM invoices WHERE invoice_type = ? AND branch_id = ? AND `status` = ?', [1, $branchId, 1])->fetch();

        $cash_boxes = $this->db->select(
            'SELECT id, `name` FROM cash_boxes WHERE `status` = ? AND branch_id = ?',
            [1, $branchId]
        )->fetchAll();

        if ($sale_invoice) {
            $cart_lists = $this->db->select(
                'SELECT sii.*, p.package_type AS product_package_type, p.unit_type AS product_unit_type,
                (SELECT SUM(item_total_price) FROM invoice_items WHERE invoice_id = ?) as total_price 
            FROM invoice_items sii
            LEFT JOIN products p ON p.id = sii.product_id
            WHERE sii.invoice_id = ?',
                [$sale_invoice['id'], $sale_invoice['id']]
            )->fetchAll();

            $seller = $this->db->select('SELECT id, user_name, phone, `address` FROM users WHERE id = ? AND `status` = ?', [$sale_invoice['user_id'], 1])->fetch();
            if ($seller) {
                // $total_debt = $this->db->select('SELECT debtor FROM customer_accounts WHERE user_id = ?', [$seller['id']])->fetch();
            }
        }

        require_once(BASE_PATH . '/resources/views/app/sales/add-sale.php');
    }

    // get invoice items ajax
    public function getSaleInvoiceItemsAjax()
    {
        $this->middleware(true, true, 'general');
        $branchId = $this->getBranchId();

        $invoice = $this->db->select('SELECT id FROM invoices WHERE invoice_type = ? AND status = ? AND branch_id = ?', [1, 1, $branchId])->fetch();

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

    // show all sales inventories
    public function showSales()
    {
        $this->middleware(true, true, 'general', true);
        $sales = $this->db->select('SELECT invoices.*, users.user_name AS seller_name FROM invoices LEFT JOIN users ON invoices.user_id = users.id WHERE invoices.status = 2 AND invoices.invoice_type = 1 ORDER BY invoices.id DESC')->fetchAll();

        require_once(BASE_PATH . '/resources/views/app/sales/sales.php');
    }

    // search product for sale
    public function searchProdutSale($request)
    {
        $this->middleware(true, true, 'general');
        $branchId = $this->getBranchId();
        $search = trim($request['customer_name']);

        $product = $this->db->select(
            "SELECT i.id, i.product_name, i.product_id,
                    i.package_price_sell, i.package_price_buy, unit_price_buy, unit_price_sell, i.quantity_in_pack
            FROM inventory i
            JOIN warehouses w ON i.warehouse_id = w.id
            WHERE i.branch_id = ?
            AND i.product_name LIKE ?
            AND w.type = 'shop'
            ORDER BY i.product_name
            LIMIT 20",
            [$branchId, "%{$search}%"]
        )->fetchAll();


        $response = [
            'status' => 'success',
            'products' => $product,
            'message' => 'lists'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    // get product infos AJAX for sale
    public function getProductInfosSale($request)
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();

        $query = '
            SELECT i.*, p.package_type, p.unit_type 
            FROM inventory i
            JOIN products p ON p.id = i.product_id
            WHERE i.id = ?';

        $params = [$request['id']];

        if ($branchId !== 'ALL') {
            $query .= ' AND i.branch_id = ?';
            $params[] = $branchId;
        }

        $productInfos = $this->db->select($query, $params)->fetch();


        $response = [
            'status' => 'success',
            'product' => $productInfos,
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    // store product
    public function productSaleStore($request)
    {
        $this->middleware(true, true, 'general', true);

        // Validation inputs
        $required = ['product_id', 'product_name', 'package_price_sell'];
        foreach ($required as $field) {
            if (empty($request[$field])) {
                $this->send_json_response(false, "اطلاعات ارسال شده مشکل دارد!");
                exit();
            }
        }

        // if (empty($request['package_qty']) && empty($request['unit_qty'])) {
        //     $this->flashMessage('error', 'لطفا تعداد بسته یا عدد را وارد نمائید!');
        //     $this->send_json_response(true, 'ok ast');
        //     exit;
        // }

        // get user infos
        $userInfo = $this->currentUser();

        // Validate general inputs
        $this->validateInputs($request);

        // Get date info
        $yearMonth = $this->calendar->getYearMonth();

        // Clean numeric inputs
        $request = $this->cleanNumbers($request, [
            'item_total_price',
            'package_price_buy',
            'package_price_sell',
            'unit_price_buy',
            'unit_price_sell'
        ]);

        $request = $this->cleanNumericFields($request, [
            'unit_qty',
            'package_qty',
            'package_price_buy',
            'package_price_sell',
            'unit_price_buy',
            'unit_price_sell',
        ]);

        // Branch
        $branchId = $this->getBranchId();

        //  Prepare invoice info
        $invoice_infos = [
            'invoice_type' => 1,
            'branch_id' => $branchId,
            'year' => $yearMonth['year'],
            'month' => $yearMonth['month'],
            'who_it' => $userInfo['name'],
        ];

        //  Create or get existing invoice
        $invoice_id = $this->invoice->InvoiceConfirm($invoice_infos);

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

    //////////////////// closing invoices /////////////////////////

    // close invoice
    public function closeSaleInvoiceStore($request)
    {
        $this->middleware(true, true, 'general');

        $this->normalizeFloatFields($request, 'total_price', 'paid_amount', 'discount');

        $total_price       = isset($request['total_price']) ? floatval($request['total_price']) : 0;
        $sale_discount     = isset($request['discount']) ? floatval($request['discount']) : 0;
        $sale_paid_amount  = isset($request['paid_amount']) ? floatval($request['paid_amount']) : 0;

        $netRemaining = $total_price - $sale_discount - $sale_paid_amount;

        if ($sale_paid_amount > ($total_price - $sale_discount)) {
            $this->flashMessage('error', 'مبلغ پرداختی نمی‌تواند بیشتر از مبلغ بِل بعد از تخفیف باشد!');
            return;
        }

        $branchId = (int)$this->getBranchId();

        $customerId = !empty($request['seller_id']) ? (int)$request['seller_id'] : (int)$this->customerId();
        if ($customerId == 1 && $total_price > $sale_paid_amount + $sale_discount) {
            $this->flashMessage('error', 'مشتری عمومی باید تسویه کند');
        }

        $userInfo = $this->currentUser();

        $invoice = $this->invoice->getInvoice($request['invoice_id'], $branchId);
        if (!$invoice) {
            throw new \Exception('Invoice not found');
        }

        $yearMonth = $this->calendar->getYearMonth();
        $invoice_items = $this->invoice->getInvoiceItems($invoice['id']);
        if (!$invoice_items) {
            $this->flashMessage('error', 'بِل خالی است!');
            return;
        }

        try {
            $this->db->beginTransaction();

            foreach ($invoice_items as $item) {

                $existingInventory = $this->db->select(
                    "SELECT * FROM inventory WHERE product_id = ? AND branch_id = ? LIMIT 1",
                    [$item['product_id'], $branchId]
                )->fetch();

                if (!$existingInventory) {
                    throw new \Exception("محصول {$item['product_name']} در سیستم تعریف نشده است!");
                }

                $sellQtyTotal = ((float)$item['package_qty'] * (float)$item['quantity_in_pack']) + (float)$item['unit_qty'];

                $fifo_movements = $this->db->select(
                    "SELECT * FROM inventory_movements 
                     WHERE product_id = ? AND branch_id = ? AND movement_type = 2 AND remaining_qty > 0 
                     ORDER BY movement_date ASC, id ASC",
                    [$item['product_id'], $branchId]
                )->fetchAll();

                $tempSellQty = $sellQtyTotal;

                if (!empty($fifo_movements)) {

                    foreach ($fifo_movements as $movement) {
                        if ($tempSellQty <= 0) break;

                        $availableInThisBatch = (float)$movement['remaining_qty'];
                        $takeQty = min($tempSellQty, $availableInThisBatch);

                        $newRemaining = $availableInThisBatch - $takeQty;
                        $this->db->update('inventory_movements', $movement['id'], ['remaining_qty'], [$newRemaining]);

                        // ثبت خروج کالا (فروش) با حفظ قیمت خریدِ همان بچ برای محاسبه سود
                        $movementData = [
                            'branch_id'          => $branchId,
                            'product_id'         => $item['product_id'],
                            'invoice_id'         => $invoice['id'],
                            'invoice_item_id'    => $item['id'],
                            'movement_type'      => 1, // خروج/فروش
                            'reference_type'     => 1,
                            'total_unit_qty'     => $takeQty,
                            'remaining_qty'      => 0,
                            'package_price_buy'  => $movement['package_price_buy'],  // قیمت خرید زمان ورود
                            'package_price_sell' => $item['package_price_sell'], // قیمت فروش فعلی
                            'unit_price_buy'     => $movement['unit_price_buy'],     // قیمت خرید واحد زمان ورود
                            'unit_price_sell'    => $item['unit_price_sell'],    // قیمت فروش واحد فعلی
                            'movement_date'      => $request['date'],
                            'warehouse_id'       => $movement['warehouse_id'],
                            'who_it'             => $userInfo['name'],
                        ];
                        $this->db->insert('inventory_movements', array_keys($movementData), $movementData);

                        $tempSellQty -= $takeQty;
                    }
                }

                // اگر هنوز مقداری مانده باشد (فروش بیش از موجودی یا نبود رکورد خرید)
                if ($tempSellQty > 0) {
                    $extraMovement = [
                        'branch_id'          => $branchId,
                        'product_id'         => $item['product_id'],
                        'invoice_id'         => $invoice['id'],
                        'invoice_item_id'    => $item['id'],
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
                        'who_it'             => $userInfo['name'],
                    ];
                    $this->db->insert('inventory_movements', array_keys($extraMovement), $extraMovement);
                }

                // آپدیت موجودی کل در جدول Inventory
                $newInventoryTotal = (float)$existingInventory['quantity'] - $sellQtyTotal;
                $this->db->update('inventory', $existingInventory['id'], ['quantity'], [$newInventoryTotal]);
            }

            // ثبت تراکنش مالی
            $this->transaction->addNewTransaction([
                'branch_id'        => $branchId,
                'ref_id'           => $invoice['id'],
                'user_id'          => $customerId,
                'transaction_type' => 1,
                'total_amount'     => $total_price,
                'discount'         => $sale_discount,
                'paid_amount'      => $sale_paid_amount,
                'transaction_date' => $request['date'],
                'description'      => $request['description'] ?? 'فروش بِل شماره ' . ($invoice['invoice_number'] ?? $invoice['id']),
                'status'           => 1,
                'who_it'           => $userInfo['name'],
                'balance'          => $netRemaining,
            ]);

            $this->notification->sendNotif([
                'branch_id' => $branchId,
                'user_id'   => $customerId,
                'ref_id'    => $invoice['id'],
                'type'      => 1,
            ]);

            if ($sale_paid_amount > 0) {
                $this->reports->updateFund([
                    'branch_id'  => $branchId,
                    'to_cash_id' => $request['source'],
                    'amount'     => $sale_paid_amount,
                    'ref_id'     => $invoice['id'],
                    'type'       => 1,
                    'user_id'    => $customerId,
                    'date'       => $request['date'],
                    'source'     => $request['source'],
                ]);
            }

            $invoice_infos = [
                'total_amount' => $total_price,
                'discount'     => $sale_discount,
                'user_id'      => $customerId,
                'date'         => $request['date'],
                'paid_amount'  => $sale_paid_amount,
                'description'  => $request['description'] ?? null,
                'year'         => $yearMonth['year'],
                'month'        => $yearMonth['month'],
                'status'       => 2,
            ];

            $inserted = $this->db->update('invoices', $invoice['id'], array_keys($invoice_infos), $invoice_infos);

            $this->db->commit();

            if ($inserted && isset($request['invoice_print'])) {
                $this->flashMessageId('success', 'بِل با موفقیت ثبت شد', $request['invoice_id']);
                return;
            }

            $this->flashMessage('success', 'بِل فروش با موفقیت بسته شد.');
        } catch (\Exception $e) {
            $this->db->rollBack();
            $this->flashMessage('error', 'خطا در ثبت بِل: ' . $e->getMessage());
        }
    }

    // edit and close invoice sale cart controllers
    // public function editSaleProductCart($id)
    // {
    //     $this->middleware(true, true, 'general', true);

    //     $product_cart = $this->db->select('SELECT * FROM invoice_items WHERE id = ?', [$id])->fetch();

    //     if ($product_cart == null) {
    //         require_once(BASE_PATH . '/404.php');
    //         exit();
    //     }

    //     $user = $this->db->select('SELECT id, user_name FROM users WHERE id = ?', [$product_cart['seller_id']])->fetch();

    //     if ($product_cart != null) {
    //         require_once(BASE_PATH . '/resources/views/app/sales/edit-sale-product-cart.php');
    //         exit();
    //     } else {
    //         require_once(BASE_PATH . '/404.php');
    //         exit();
    //     }
    // }

    // edit sale product cart store
    // public function editSaleProductCartStore($request, $id)
    // {
    //     $this->middleware(true, true, 'general', true, $request, true);

    //     if ($request['package_qty'] == '' && $request['unit_qty'] == '') {
    //         $this->flashMessage('error', 'لطفا تعداد بسته یا عدد را وارد نمائید!');
    //     }

    //     $product_cart = $this->db->select('SELECT * FROM invoice_items WHERE `id` = ?', [$id])->fetch();
    //     if (!$product_cart) {
    //         require_once(BASE_PATH . '/404.php');
    //         exit;
    //     }

    //     $request = $this->cleanNumbers($request, ['package_qty', 'unit_qty']);




    //     $unit_prices = $this->calculateUnitPrices($product_cart);
    //     $unit_price = $unit_prices['sell'];

    //     // new quantity
    //     $request['quantity'] = ($product_cart['quantity_in_pack'] * (int)$request['package_qty']) + (int)$request['unit_qty'];

    //     // $item_discount = 0;
    //     // if ($request['discount'] != 0) {
    //     //     $item_discount =  intval($request['discount']);
    //     // }

    //     $request['item_total_price'] = $unit_price * $request['quantity'];  // - $item_discount

    //     $this->db->update('invoice_items', $id, array_keys($request), $request);
    //     $this->flashMessageTo('success', _success, url('add-sale'));
    // }

    // delete saleproduct from cart
    // public function deleteSaleProductCart($id)
    // {
    //     $this->middleware(true, true, 'general', true);

    //     if (!is_numeric($id)) {
    //         $this->flashMessage('error', 'لطفا اطلاعات درست ارسال نمائید!');
    //     }

    //     $product_cart = $this->db->select('SELECT id, product_id, quantity FROM invoice_items WHERE `id` = ?', [$id])->fetch();
    //     if (!$product_cart) {
    //         require_once(BASE_PATH . '/404.php');
    //         exit;
    //     }
    //     $inventory = $this->db->select('SELECT id, quantity FROM inventory WHERE `product_id` = ?', [$product_cart['product_id']])->fetch();

    //     $newQuantity = intval($product_cart['quantity']) + intval($inventory['quantity']);

    //     $this->db->update('inventory', $inventory['id'], ['quantity'], [$newQuantity]);
    //     $this->db->delete('invoice_items', $id);
    //     $this->flashMessage('success', _success);
    //     exit;
    // }

    // delete sale invoice from buy product form
    // NOTE add general method for delete invoce
    // public function deleteSaleInvoice($id)
    // {
    //     $this->middleware(true, true, 'general', true);

    //     if (!is_numeric($id)) {
    //         $this->flashMessage('error', 'لطفا اطلاعات درست ارسال نمائید!');
    //     }
    //     $branchId = $this->getBranchId();
    //     $invoice = $this->db->select('SELECT id FROM invoices WHERE branch_id = ? AND `id` = ?', [$branchId, $id])->fetch();

    //     if (!$invoice) {
    //         require_once(BASE_PATH . '/404.php');
    //         exit;
    //     }

    //     $this->db->delete('invoices', $id);
    //     $this->flashMessage('success', _success);
    //     exit;
    // }

    public function InvoicePrint($id)
    {
        $this->middleware(true, true, 'general', true);

        $userInfos = $this->currentUser();

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

        $branchId = $this->getBranchId();

        $factor_infos = $this->db->select('SELECT * FROM factor_settings WHERE branch_id = ?', [$branchId])->fetch();

        return [
            'invoice' => $sale_invoice_print,
            'items' => $invoice_data,
            'factor_infos' => $factor_infos,
            'userinfos' => $userInfos,
        ];
    }

    // sala invoice details page
    public function saleInvoiceDetails($id)
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();

        $invoice = $this->invoice->getInvoiceDetails($id);

        $user_infos = $invoice['user_infos'];
        $invoice_items = $invoice['items'];
        $invoice = $invoice['invoice'];

        $profits = $this->getInvoiceProfit($id);

        require_once(BASE_PATH . '/resources/views/app/sales/sale-invoice-details.php');
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
}
