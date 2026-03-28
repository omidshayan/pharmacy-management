<?php

namespace App;

// require_once 'Http/Controllers/App.php';
require_once 'Http/Models/Calendar.php';
require_once 'Http/Models/Invoice.php';
require_once 'Http/Models/Buy.php';
require_once 'Http/Models/Notification.php';
require_once 'Http/Models/Transaction.php';
require_once 'Http/Models/Reports.php';

use Models\Calendar\Calendar;
use Models\Invoice\Invoice;
use Models\Buy\Buy;
use Models\Notification\Notification;
use Models\Transaction\Transaction;
use Models\Reports\Reports;
use PDO;
use SensitiveParameter;

class ProductInventory extends App
{
    private $calendar;
    private $invoice;
    private $buy;
    private $transaction;
    private $notification;
    private $reports;

    public function __construct()
    {
        parent::__construct();
        $this->calendar = new Calendar();
        $this->invoice = new Invoice();
        $this->buy = new Buy();
        $this->notification = new Notification();
        $this->transaction = new Transaction();
        $this->reports = new Reports();
    }

    // add expense page
    public function addProductInventory()
    {
        $this->middleware(true, true, 'general', true);

        $branchId = $this->getBranchId();

        $purchase_invoices = $this->db->select(
            'SELECT * FROM invoices WHERE invoice_type = ? AND `status` = ? AND branch_id = ?',
            [2, 1, $branchId]
        )->fetch();

        $expire_date = $this->db->select(
            'SELECT expiration_date FROM settings WHERE branch_id = ?',
            [$branchId]
        )->fetch();

        $cash_boxes = $this->db->select(
            'SELECT id, `name` FROM cash_boxes WHERE `status` = ? AND branch_id = ?',
            [1, $branchId]
        )->fetchAll();

        // Warehouse check
        $warehouses = [];
        $warehouse = $this->db->select(
            'SELECT warehouse FROM settings WHERE branch_id = ?',
            [$branchId]
        )->fetch();

        if (!empty($warehouse) && $warehouse['warehouse'] == 1) {

            $warehouses = $this->db->select(
                'SELECT id, warehouse_name FROM warehouses 
         WHERE branch_id = ? AND is_active = ?',
                [$branchId, 1]
            )->fetchAll();
        }

        require_once(BASE_PATH . '/resources/views/app/product-inventory/add-product-inventory.php');
    }

    // get invoice items ajax
    public function getInvoiceItemsAjax()
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();

        $invoice = $this->db->select(
            'SELECT id FROM invoices 
         WHERE invoice_type = ? AND status = ? AND branch_id = ?',
            [2, 1, $branchId]
        )->fetch();

        if (!$invoice) {
            $this->send_json_response(true, 'empty', [
                'items' => []
            ]);
            exit();
        }

        $items = $this->invoice->getInvoiceProductItems($invoice['id']);

        // check warehouses
        $warehouses = [];
        $warehouse = $this->db->select(
            'SELECT warehouse FROM settings WHERE branch_id = ?',
            [$branchId]
        )->fetch();

        if (!empty($warehouse) && $warehouse['warehouse'] == 1) {

            $warehouses = $this->db->select(
                'SELECT id, warehouse_name FROM warehouses 
                WHERE branch_id = ? AND is_active = ?',
                [$branchId, 1]
            )->fetchAll();
        }

        $this->send_json_response(true, 'ok', [
            'items' => $items,
            'invoice_id' => $invoice['id'],
            'warehouses' => $warehouses,
        ]);

        exit();
    }

    // search product for inventory
    public function searchProdut($request)
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();

        $product = $this->db->select(
            "SELECT *
            FROM products
            WHERE `status` = 1
            AND product_name LIKE ?
            AND branch_id = ?
            ORDER BY product_name
            LIMIT 20",
            ['%' . trim($request['customer_name']) . '%', $branchId]
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

    // get product infos AJAX
    // public function getProductInfos($request)
    // {
    //     $this->middleware(true, true, 'general', true);
    //     $productInfos = $this->db->select('SELECT * FROM products WHERE id LIKE ?', ['%' . $request['id'] . '%'])->fetch();
    //     $inventory = $this->db->select('SELECT * FROM inventory WHERE product_id = ?', [$request['id']])->fetch();
    //     $response = [
    //         'status' => 'success',
    //         'product' => $productInfos,
    //         'inventory' => $inventory,
    //     ];
    //     header('Content-Type: application/json');
    //     echo json_encode($response);
    //     exit();
    // }

    // search seller for inventory
    public function searchSeller($request)
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();
        $keyword  = trim($request['customer_name']);

        $seller = $this->db->select(
            "SELECT *
                FROM users
                WHERE is_seller = ?
                AND user_name LIKE ?
                AND branch_id = ?
                ORDER BY user_name
                LIMIT 20",
            [1, '%' . $keyword . '%', $branchId]
        )->fetchAll();

        echo json_encode([
            'status'  => 'success',
            'sellers' => $seller,
            'message' => 'lists'
        ]);
        exit();
    }

    // store product
    public function productInventoryStore($request)
    {
        $this->middleware(true, true, 'general', true);

        $required = ['product_id', 'product_name', 'package_price_buy'];
        foreach ($required as $field) {
            if (empty($request[$field])) {
                $this->send_json_response(false, "قیمت دوا ارسال نشده یا به درستی ثبت نشده است");
                exit();
            }
        }

        $this->validateInputs($request);

        $yearMonth = $this->calendar->getYearMonth();

        $request = $this->cleanNumbers($request, [
            'item_total_price',
            'package_price_buy',
            'package_price_sell',
            'quantity'
        ]);

        $request = $this->cleanNumericFields($request, [
            'unit_qty',
            'package_qty',
            'package_price_buy',
            'package_price_sell',
            'quantity_in_pack'
        ]);
        $type = 2;
        $userInfos = $this->currentUser();

        $purchase_invoice = [
            'invoice_type' => $type,
            'branch_id'    => $userInfos['branch_id'],
            'year'         => $yearMonth['year'],
            'month'        => $yearMonth['month'],
            'who_it'       => $userInfos['name'],
        ];

        $invoice_id = $this->invoice->InvoiceConfirm($purchase_invoice);

        $quantity_in_pack = !empty($request['quantity_in_pack'])
            ? floatval($request['quantity_in_pack'])
            : 1;

        if (
            (
                (isset($request['unit_price_buy']) && $request['unit_price_buy'] !== '' && !is_null($request['unit_price_buy'])) ||
                (isset($request['unit_price_sell']) && $request['unit_price_sell'] !== '' && !is_null($request['unit_price_sell']))
            )
            && $quantity_in_pack <= 1
        ) {
            $this->send_json_response(false, 'برای ثبت قیمت واحد، تعداد داخل بسته باید بیشتر از ۱ باشد');
            exit();
        }

        if (
            !is_null($request['unit_type']) &&
            isset($request['unit_price_buy']) && $request['unit_price_buy'] !== '' &&
            isset($request['unit_price_sell']) && $request['unit_price_sell'] !== ''
        ) {

            $unit_qty    = 1;
            $package_qty = 0;

            $quantity = 1;

            $unit_price = $request['unit_price_buy'];

            $item_total_price = $unit_price;

            $unit_price_sell = $request['unit_price_sell'];
        } else {

            $unit_qty    = 0;
            $package_qty = 1;

            $quantity = 1;

            $unit_price = null;
            $unit_price_sell = null;

            $item_total_price = $request['package_price_buy'];
        }

        $invoice_items = [
            'branch_id'          => $userInfos['branch_id'],
            'invoice_id'         => $invoice_id,
            'product_id'         => $request['product_id'],
            'product_name'       => $request['product_name'],
            'unit_qty'           => $unit_qty,
            'package_qty'        => $package_qty,
            'quantity_in_pack'   => $quantity_in_pack,
            'package_price_buy'  => $request['package_price_buy'],
            'package_price_sell' => $request['package_price_sell'],
            'unit_price_buy'     => $unit_price,
            'unit_price_sell'    => $unit_price_sell,
            'item_total_price'   => $item_total_price,
            'quantity'           => $quantity,
        ];

        $exist_product = $this->invoice->getInvoiceItem(
            $invoice_id,
            $request['product_id'],
            $userInfos['branch_id']
        );

        if (!$exist_product) {

            $this->db->insert(
                'invoice_items',
                array_keys($invoice_items),
                $invoice_items
            );
        } else {

            $new_quantity = $exist_product['quantity'] + $invoice_items['quantity'];

            $new_package_qty = $exist_product['package_qty'] + $invoice_items['package_qty'];
            $new_unit_qty    = $exist_product['unit_qty'] + $invoice_items['unit_qty'];

            $new_total_price = $exist_product['item_total_price'] + $invoice_items['item_total_price'];

            $update_data = [
                'quantity'           => $new_quantity,
                'package_qty'        => $new_package_qty,
                'unit_qty'           => $new_unit_qty,
                'item_total_price'   => $new_total_price,
                'package_price_buy'  => $invoice_items['package_price_buy'],
            ];

            $this->db->update(
                'invoice_items',
                $exist_product['id'],
                array_keys($update_data),
                $update_data
            );
        }

        $invoice_items = $this->invoice->getInvoiceProductItems($invoice_id);

        // check warehouses
        $warehouses = [];
        $warehouse = $this->db->select(
            'SELECT warehouse FROM settings WHERE branch_id = ?',
            [$userInfos['branch_id']]
        )->fetch();

        if (!empty($warehouse) && $warehouse['warehouse'] == 1) {

            $warehouses = $this->db->select(
                'SELECT id, warehouse_name FROM warehouses 
            WHERE branch_id = ? AND is_active = ?',
                [$userInfos['branch_id'], 1]
            )->fetchAll();
        }

        $this->send_json_response(true, _added, [
            'items'       => $invoice_items,
            'invoice_id'  => $invoice_id,
            'warehouses'  => $warehouses,
        ]);

        exit();
    }

    // update warehouse item
    public function itemUpdateWarehouse($request, $id)
    {

        $this->middleware(true, true, 'general');

        $this->db->beginTransaction();

        try {
            $item = $this->db->select('SELECT * FROM invoice_items WHERE id = ?', [$id])->fetch();
            if (!$item) {
                $this->send_json_response(false, 'آیتم یافت نشد');
                return;
            }

            $expireInput = $request['expiration_date'] ?? null;
            $expirationTimestamp = null;

            if (!empty($expireInput)) {
                if (strpos($expireInput, '/') !== false) {
                    $parts = explode('/', $expireInput);
                    if (count($parts) == 3) {
                        $gy = 0;
                        $gm = 0;
                        $gd = 0;
                        $this->jalali_to_gregorian($parts[0], $parts[1], $parts[2], $gy, $gm, $gd);
                        $expirationTimestamp = strtotime("$gy-$gm-$gd");
                    }
                } else {
                    $expirationTimestamp = strtotime($expireInput);
                }
            }

            $unitType = $request['unit_type'] ?? null;
            $unitPriceBuy = isset($request['unit_price_buy']) ? floatval($request['unit_price_buy']) : null;
            $unitPriceSell = isset($request['unit_price_sell']) ? floatval($request['unit_price_sell']) : null;
            $packagePriceBuy = isset($request['package_price_buy']) ? floatval($request['package_price_buy']) : null;
            $packagePriceSell = isset($request['package_price_sell']) ? floatval($request['package_price_sell']) : null;

            if (isset($unitType) && $unitType !== '' && $unitType !== 'null') {
                if ($unitPriceBuy === null || $unitPriceBuy <= 0) {
                    $this->send_json_response(false, 'قیمت خرید عدد باید بزرگتر از صفر باشد');
                    return;
                }
                if ($unitPriceSell === null || $unitPriceSell <= 0) {
                    $this->send_json_response(false, 'قیمت فروش عدد باید بزرگتر از صفر باشد');
                    return;
                }
            }

            if ($packagePriceBuy === null || $packagePriceBuy <= 0) {
                $this->send_json_response(false, 'قیمت خرید بسته باید بزرگتر از صفر باشد');
                return;
            }
            if ($packagePriceSell === null || $packagePriceSell <= 0) {
                $this->send_json_response(false, 'قیمت فروش بسته باید بزرگتر از صفر باشد');
                return;
            }

            $update_data = [
                'warehouse_id'       => $request['warehouse_id'] ?? $item['warehouse_id'],
                'location'           => $request['note'] ?? $item['location'],
                'package_price_buy'  => $packagePriceBuy,
                'package_price_sell' => $packagePriceSell,
                'unit_price_buy'     => $unitPriceBuy,
                'unit_price_sell'    => $unitPriceSell,
                'expiration_date'    => $expirationTimestamp,
            ];

            // اگر تایم استمپ خالی بود، فیلد را از آرایه حذف کن تا دیتای قبلی در دیتابیس دست‌نخورده بماند
            if (empty($expirationTimestamp)) {
                unset($update_data['expiration_date']);
            }

            $updated = $this->db->update('invoice_items', $id, array_keys($update_data), $update_data);

            if ($updated) {
                $this->db->commit();
                $this->send_json_response(true, _added);
            } else {
                $this->db->rollBack();
                $this->send_json_response(false, 'خطا در به‌روزرسانی رکورد');
            }
        } catch (\Exception $e) {
            $this->db->rollBack();
            $this->send_json_response(false, 'خطای سرور: ' . $e->getMessage());
        }
    }

    ////////////////////////// closing invoices ///////////////////////////////

    // close invoice 
    public function closeBuyInvoiceStore($request)
    {
        $this->middleware(true, true, 'general');
        $this->normalizeFloatFields($request, 'total_price', 'paid_amount', 'discount');

        $total_price     = (float)$request['total_price'];
        $buy_discount    = (float)$request['discount'];
        $buy_paid_amount = (float)$request['paid_amount'];
        $netRemaining    = $total_price - $buy_discount - $buy_paid_amount;

        if ($buy_paid_amount > ($total_price - $buy_discount)) {
            $this->flashMessage('error', 'مبلغ پرداختی نمی‌تواند بیشتر از مبلغ بِل!');
            return;
        }

        $customerId = !empty($request['seller_id']) ? (int)$request['seller_id'] : (int)$this->customerId();

        if ($customerId == 1 && $total_price > $buy_paid_amount + $buy_discount) {
            $this->flashMessage('error', 'فروشنده عمومی باید تسویه کند');
        }

        $branchId = (int)$this->getBranchId();
        $userInfo = $this->currentUser();
        $invoice  = $this->invoice->getInvoice($request['invoice_id'], $branchId);

        if (!$invoice) throw new \Exception('Invoice not found');

        $yearMonth = $this->calendar->getYearMonth();
        $invoice_items = $this->invoice->getInvoiceItems($invoice['id']);

        $storeWarehouse = $this->db->select("SELECT id FROM warehouses WHERE branch_id = ? AND type = 'shop' LIMIT 1", [$branchId])->fetch();

        try {
            $this->db->beginTransaction();

            foreach ($invoice_items as $item) {
                $finalWarehouseId = $item['warehouse_id'] ?? $request['warehouse_id'] ?? (int)$storeWarehouse['id'];

                $totalInUnits = ((float)$item['package_qty'] * (float)$item['quantity_in_pack']) + (float)$item['unit_qty'];

                if ($totalInUnits > 0) {
                    $movementData = [
                        'branch_id'          => $branchId,
                        'product_id'         => $item['product_id'],
                        'invoice_id'         => $invoice['id'],
                        'invoice_item_id'    => $item['id'],
                        'movement_type'      => 2,
                        'reference_type'     => 2,
                        'total_unit_qty'     => $totalInUnits,
                        'remaining_qty'      => $totalInUnits,
                        'package_price_buy'  => $item['package_price_buy'],
                        'package_price_sell' => $item['package_price_sell'],
                        'unit_price_buy'     => $item['unit_price_buy'],
                        'unit_price_sell'    => $item['unit_price_sell'],
                        'movement_date'      => $request['buy_date'],
                        'warehouse_id'       => $finalWarehouseId,
                        'expiration_date'    => $item['expiration_date'] ?: null,
                        'who_it'             => $userInfo['name'],
                    ];
                    $this->db->insert('inventory_movements', array_keys($movementData), $movementData);
                }

                // آپدیت جدول انبار (Inventory)
                $existingInventory = $this->db->select("SELECT id, quantity FROM inventory WHERE product_id = ? AND branch_id = ? AND warehouse_id = ? LIMIT 1", [$item['product_id'], $branchId, $finalWarehouseId])->fetch();

                $priceData = [
                    'package_price_buy'  => $item['package_price_buy'],
                    'package_price_sell' => $item['package_price_sell'],
                    'unit_price_buy'     => $item['unit_price_buy'],
                    'unit_price_sell'    => $item['unit_price_sell'],
                ];

                if ($existingInventory) {
                    $newQty = (float)$existingInventory['quantity'] + $totalInUnits;
                    $updateFields = array_merge(['quantity'], array_keys($priceData));
                    $updateValues = array_merge([$newQty], array_values($priceData));
                    $this->db->update('inventory', $existingInventory['id'], $updateFields, $updateValues);
                } else {
                    $insertData = array_merge([
                        'branch_id'    => $branchId,
                        'product_id'   => $item['product_id'],
                        'product_name' => $item['product_name'],
                        'quantity'     => $totalInUnits,
                        'warehouse_id' => $finalWarehouseId,
                        'quantity_in_pack' => $item['quantity_in_pack']
                    ], $priceData);
                    $this->db->insert('inventory', array_keys($insertData), array_values($insertData));
                }
            }

            $this->transaction->addNewTransaction([
                'branch_id'        => $branchId,
                'ref_id'           => $invoice['id'],
                'user_id'          => $customerId,
                'transaction_type' => 2,
                'total_amount'     => $total_price,
                'discount'         => $buy_discount,
                'paid_amount'      => $buy_paid_amount,
                'transaction_date' => $request['buy_date'],
                'description'      => $request['description'] ?? 'خرید بِل شماره ' . $invoice['id'],
                'status'           => 1,
                'who_it'           => $userInfo['name'],
                'balance'          => $netRemaining,
            ]);

            $this->notification->sendNotif(['branch_id' => $branchId, 'user_id' => $customerId, 'ref_id' => $invoice['id'], 'type' => 2]);

            if ($buy_paid_amount > 0) {
                $this->reports->updateFund([
                    'branch_id'  => $branchId,
                    'to_cash_id' => $request['source'],
                    'amount'     => $buy_paid_amount,
                    'ref_id'     => $invoice['id'],
                    'type'       => 2,
                    'user_id'    => $customerId,
                    'date'       => $request['buy_date'],
                    'source'     => $request['source'],
                ]);
            }

            $imageName = $this->handleImageUpload($request['image'], 'images/invoices');

            $invoice_infos = [
                'total_amount' => $total_price,
                'discount'     => $buy_discount,
                'user_id'      => $customerId,
                'date'         => $request['buy_date'],
                'paid_amount'  => $buy_paid_amount,
                'year'         => $yearMonth['year'],
                'month'        => $yearMonth['month'],
                'status'       => 2,
                'image'        => $imageName
            ];
            $inserted = $this->db->update('invoices', $invoice['id'], array_keys($invoice_infos), $invoice_infos);

            $this->db->commit();

            if ($inserted && isset($request['invoice_print'])) {
                $this->flashMessageId('success', 'بِل با موفقیت ثبت شد', $request['invoice_id']);
                return;
            }

            $this->flashMessage('success', 'بِل خرید با موفقیت ثبت شد.');
        } catch (\Exception $e) {
            $this->db->rollBack();
            $this->flashMessage('error', 'خطا: ' . $e->getMessage());
        }
    }

    // cart controllers
    public function editProductCart($id)
    {
        $this->middleware(true, true, 'general', true);

        $product_cart = $this->db->select('SELECT * FROM invoice_items WHERE id = ?', [$id])->fetch();

        $product =  $this->db->select('SELECT unit_type, package_type, unit_type FROM products WHERE `id` = ?', [$product_cart['product_id']])->fetch();

        if ($product_cart === null || $product_cart === false) {
            require_once(BASE_PATH . '/404.php');
            exit();
        }

        require_once(BASE_PATH . '/resources/views/app/product-inventory/edit-product-cart.php');
        exit();
    }

    // edit product cart store
    public function editProductCartStore($request, $id)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        if ($request['package_qty'] == '' && $request['unit_qty'] == '') {
            $this->flashMessage('error', 'لطفا تعداد بسته یا عدد را وارد نمائید!');
        }

        $product_cart = $this->db->select('SELECT * FROM invoice_items WHERE `id` = ?', [$id])->fetch();
        if (!$product_cart) {
            require_once(BASE_PATH . '/404.php');
            exit;
        }

        $unit_prices = $this->calculateUnitPrices($product_cart);
        $unit_price = $unit_prices['buy'];

        $request = $this->cleanNumbers($request, ['package_qty', 'unit_qty', 'discount']);

        $request['quantity'] = ($product_cart['quantity_in_pack'] * (int)$request['package_qty']) + (int)$request['unit_qty'];

        $old_discount = (int)($product_cart['discount'] ?? 0);
        $raw = isset($request['discount']) ? trim($request['discount']) : '';

        if ($raw === '') {
            $final_discount = $old_discount;
        } else {
            $final_discount = (int)$raw;
        }

        if ($final_discount < 0) {
            $final_discount = 0;
        }

        $request['discount'] = $final_discount;

        $request['item_total_price'] = max(0, ($unit_price * $request['quantity']) - $final_discount);

        $this->db->update('invoice_items', $id, array_keys($request), $request);

        header('Location: ' . url('add-product-inventory'));
        exit;
    }

    // delete product from cart
    public function deleteProductCart($id)
    {
        $this->middleware(true, true, 'general', true);

        if (!is_numeric($id)) {
            $this->flashMessage('error', 'لطفا اطلاعات درست ارسال نمائید!');
        }

        $product_cart = $this->db->select('SELECT id FROM invoice_items WHERE `id` = ?', [$id])->fetch();

        if (!$product_cart) {
            require_once(BASE_PATH . '/404.php');
            exit;
        }

        $result = $this->db->delete('invoice_items', $id);

        if ($result) {
            $this->send_json_response(true, _added, [
                'success' => true
            ]);
        } else {
            $this->flashMessage('error', _error);
        }
    }

    // delete invoice from buy product form
    public function deleteInvoice($id)
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
