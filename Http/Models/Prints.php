<?php

namespace Models\Prints;

use App\App;

class Prints extends App
{
    // get invoice infos
    public function saleInvoicePrint($id)
    {
        $sale_invoice_print = $this->db->select(
            'SELECT 
                si.*, 
                u.user_name 
             FROM sales_invoices si 
             LEFT JOIN users u ON u.id = si.seller_id 
             WHERE si.id = ?',
            [$id]
        )->fetch();

        if (!$sale_invoice_print) {
            require_once BASE_PATH . '/404.php';
            exit();
        }

        $invoice_items_print = $this->db->select('SELECT * FROM sale_invoice_items WHERE invoice_id = ?', [$id])->fetchAll();
        // $seller_name = $sale_invoice['user_name'] ?? 'عمومی';

        return [
            $sale_invoice_print,
            $invoice_items_print,
        ];
    }
}
