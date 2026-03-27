<?php

namespace App;

require_once 'Http/Controllers/App.php';
require_once 'Http/Models/Calendar.php';
require_once 'Http/Models/Invoice.php';

use Models\Calendar\Calendar;
use Models\Invoice\Invoice;

class PurchaseInvoices extends App
{
    private $calendar;
    private $invoice;
    public function __construct()
    {
        parent::__construct();
        $this->calendar = new Calendar();
        $this->invoice = new Invoice();
    }

    // invoices page
    public function purchaseInvoices()
    {
        $this->middleware(true, true, 'general', true);

        $branchId = $this->getBranchId();

        if ($branchId !== 'ALL' && !is_numeric($branchId)) {
            throw new \Exception('Invalid branch access');
        }

        $sql = "SELECT invoices.*, users.user_name AS seller_name
        FROM invoices
        LEFT JOIN users ON invoices.user_id = users.id
        WHERE invoices.status = 2 AND invoices.invoice_type = 2";

        $params = [];

        if ($branchId !== 'ALL') {
            $sql .= " AND invoices.branch_id = ?";
            $params[] = $branchId;
        }

        $sql .= " ORDER BY invoices.id DESC";

        $invoices = $this->db->select($sql, $params)->fetchAll();

        require_once(BASE_PATH . '/resources/views/app/purchase-invoices/purchase-invoices.php');
    }

    // details buy invoice
    public function purchaseInvoicesDetails($id)
    {
        $this->middleware(true, true, 'general');
        $type = 2;
        $invoice = $this->invoice->getInvoiceDetails($id, $type);
        $invoice_items = $this->invoice->getInvoiceItems($invoice['id']);
        require_once(BASE_PATH . '/resources/views/app/purchase-invoices/purchase-invoice-details.php');
        exit();
    }

    // edit buy invoice page
    public function editBuyInvoice($id)
    {
        $this->middleware(true, true, 'general', true);
        $purchase_invoices = $this->db->select('SELECT * FROM invoices WHERE id = ?', [$id])->fetch();

        if (!$purchase_invoices) {
            require_once(BASE_PATH . '/404.php');
            exit();
        }

        $cart_lists = $this->db->select('SELECT *, (SELECT SUM(item_total_price) FROM invoice_items WHERE invoice_id = ?) as total_price FROM invoice_items WHERE invoice_id = ?', [$purchase_invoices['id'], $purchase_invoices['id']])->fetchAll();

        // get user infos
        $user = $this->db->select('SELECT id, user_name, phone, `address` FROM users WHERE id = ? AND `status` = ?', [$purchase_invoices['user_id'], 1])->fetch();

        if ($user) {
            $balance = $this->db->select('SELECT balance FROM account_balances WHERE user_id = ?', [$user['id']])->fetch();
        }
        require_once(BASE_PATH . '/resources/views/app/purchase-invoices/edit-invoice.php');
        exit();
    }

    // search product for inventory
    public function searchProdut($request)
    {
        $this->middleware(true, true, 'students');
        $product = $this->db->select(
            "SELECT id, product_name 
             FROM products 
             WHERE product_name LIKE ? 
             ORDER BY product_name 
             LIMIT 20",
            ['%' . strtolower($request['customer_name']) . '%']
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
}
