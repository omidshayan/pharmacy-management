<?php

namespace App;

class PaymentSlip extends App
{
    // main funds
    public function paymentSlip()
    {
        $this->middleware(true, true, 'general', true);
        // $cash_drawer = $this->db->select('SELECT * FROM cash_drawer')->fetchAll();
        require_once(BASE_PATH . '/resources/views/app/payment-slip/payment-slip.php');
    }

    // fund store
    public function fundStore($request)
    {
        $this->middleware(true, true, 'general', true, $request, true);
        if ($request['name'] == '') {
            $this->flashMessage('error', _emptyInputs);
        }
        $request = $this->validateInputs($request);

        $name = $this->db->select('SELECT `name` FROM cash_drawer WHERE `name` = ?', [$request['name']])->fetch();
        if (!empty($name['name'])) {
            $this->flashMessage('error', _repeat);
        } else {
            $this->db->insert('cash_drawer', array_keys($request), $request);
            $this->flashMessage('success', _success);
        }
    }
}
