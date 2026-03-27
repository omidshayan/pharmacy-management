<?php

namespace App;

require_once 'Http/Models/Calendar.php';

class CashBoxes extends App
{
    // Cash Boxes page
    public function viewCashBoxes()
    {
        $this->middleware(true, true, 'general', true);

        $branchId = $this->getBranchId();

        $cash_boxes = $this->db->select('SELECT 
            cb.*,
            COALESCE(SUM(
                CASE
                    WHEN ct.to_cash_id = cb.id THEN ct.amount
                    WHEN ct.from_cash_id = cb.id THEN -ct.amount
                    ELSE 0
                END
            ), 0) AS balance
        FROM cash_boxes cb
        LEFT JOIN cash_transactions ct 
            ON ct.to_cash_id = cb.id 
            OR ct.from_cash_id = cb.id
        WHERE cb.branch_id = ?
        GROUP BY cb.id;
            ', [$branchId])->fetchAll();
        // $cash_transactions = $this->db->select('SELECT * FROM cash_transactions WHERE branch_id = ?', [$branchId])->fetchAll();

        require_once(BASE_PATH . '/resources/views/app/cash-boxes/views/view-cash-boxes.php');
    }


    //////////////////// manage cash boxes /////////////////////
    // Cash Boxes page
    public function cashBoxes()
    {
        $this->middleware(true, true, 'general', true);

        $branchId = $this->getBranchId();

        $cash_boxes = $this->db->select('SELECT * FROM cash_boxes WHERE branch_id = ?', [$branchId])->fetchAll();

        require_once(BASE_PATH . '/resources/views/app/cash-boxes/cash-boxes.php');
    }

    // cash box store
    public function cashBoxStore($request)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        if (
            $request['name'] == '' ||
            !isset($request['type']) ||
            !isset($request['currency']) ||
            !isset($request['allow_negative'])
        ) {
            $this->flashMessage('error', _emptyInputs);
            return;
        }

        $branchId = $this->getBranchId();

        $item = $this->db->select('SELECT `name` FROM cash_boxes WHERE `name` = ? AND branch_id = ?', [$request['name'], $branchId])->fetch();

        if ($item) {
            $this->flashMessage('error', _repeat);
        } else {
            $this->db->insert('cash_boxes', array_keys($request), $request);
            $this->flashMessage('success', _success);
        }
    }

    // edit cash box page
    public function editCashBox($id)
    {
        $this->middleware(true, true, 'general', true);

        $branchId = $this->getBranchId();

        $item = $this->db->select('SELECT * FROM cash_boxes WHERE id = ? AND branch_id = ?', [$id, $branchId])->fetch();

        if ($item) {
            require_once(BASE_PATH . '/resources/views/app/cash-boxes/edit-cash-box.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // edit cash box store
    public function editCashBoxStore($request, $id)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        if (
            $request['name'] == '' ||
            !isset($request['type']) ||
            !isset($request['currency']) ||
            !isset($request['allow_negative'])
        ) {
            $this->flashMessage('error', _emptyInputs);
            return;
        }

        $branchId = $this->getBranchId();
        $item = $this->db->select('SELECT * FROM cash_boxes WHERE id = ? AND branch_id = ?', [$id, $branchId])->fetch();

        if ($item != null) {

            if ($item) {
                if ($item['id'] != $id) {
                    $this->flashMessage('error', 'نام وارد شده تکراری است.');
                }
            }

            $this->db->update('cash_boxes', $id, array_keys($request), $request);
            $this->flashMessageTo('success', _success, url('cash-boxes'));
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // cashBoxDetails detiles page
    public function cashBoxDetails($id)
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();

        $item = $this->db->select('SELECT * FROM cash_boxes WHERE id = ? AND branch_id = ?', [$id, $branchId])->fetch();
        if ($item != null) {
            require_once(BASE_PATH . '/resources/views/app/cash-boxes/cash-box-details.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // change status changeStatusCashBox
    public function changeStatusCashBox($id)
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();

        $item = $this->db->select('SELECT * FROM cash_boxes WHERE id = ? AND branch_id = ?', [$id, $branchId])->fetch();

        if (!$item) {
            require BASE_PATH . '/404.php';
            exit;
        }

        $newState = $item['status'] == 1 ? 2 : 1;
        $this->db->update('cash_boxes', $item['id'], ['status'], [$newState]);
        $this->send_json_response(true, _success, $newState);
    }



























    // main funds
    public function moneyBalance()
    {
        $this->middleware(true, true, 'general', true);
        $branchId = $this->getBranchId();

        $report = $this->getDailyReport('today');

        $fund = $this->db->select('SELECT * FROM funds WHERE branch_id = ?', [$branchId])->fetch();

        $fundTransactions = $this->db->select('SELECT * FROM fund_transactions WHERE branch_id = ?', [$branchId])->fetchAll();

        require_once(BASE_PATH . '/resources/views/app/funds/maney-balance.php');
    }

    // center funds
    public function centerFund()
    {
        $this->middleware(true, true, 'general', true);
        $branchId = $this->getBranchId();

        $fund = $this->db->select('SELECT * FROM funds WHERE branch_id = ?', [$branchId])->fetch();

        $centerFundTran = $this->db->select('SELECT * FROM center_fund_transactions WHERE branch_id = ?', [$branchId])->fetchAll();

        require_once(BASE_PATH . '/resources/views/app/funds/center-fund.php');
    }

    // transfer to main fund
    // public function transferToMainFund($request)
    // {
    //     $this->middleware(true, true, 'general', true, $request, true);

    //     $branchId = $this->getBranchId();

    //     $fund = $this->db->select('SELECT * FROM funds WHERE branch_id = ?', [$branchId])->fetch();

    //     if (!$fund) {
    //         $this->flashMessage('error', 'صندوق مورد نظر یافت نشد!');
    //         return false;
    //     }

    //     $income = (float)$fund['income'];
    //     $total  = (float)$fund['total'];

    //     if ($income <= 0) {
    //         $this->flashMessage('error', 'دخل موجودی ندارد!');
    //         return false;
    //     }

    //     $newTotal = $total + $income;
    //     $newIncome = 0;

    //     $yearMonth = $this->calendar->getYearMonth();

    //     $this->db->update(
    //         'funds',
    //         $fund['id'],
    //         ['income', 'total'],
    //         [$newIncome, $newTotal]
    //     );

    //     $fundTransaction = [
    //         'branch_id' => $branchId,
    //         'fund_type_from' => 1,
    //         'amount' => $income,
    //         'year' => $yearMonth['year'],
    //         'month' => $yearMonth['month'],
    //         'who_it' => $request['who_it'],
    //     ];

    //     $this->db->insert('fund_transactions', array_keys($fundTransaction), $fundTransaction);

    //     $this->flashMessage('success', _success);
    // }

    // transfer to main fund
    // public function transferToCenterFund($request)
    // {
    //     $this->middleware(true, true, 'general', true, $request, true);

    //     $branchId = $this->getBranchId();

    //     $amount      = (float)$request['amount'];
    //     $description = trim($request['description']);

    //     if ($amount <= 0) {
    //         $this->flashMessage('error', 'لطفا عدد معتبر وارد نمائید!');
    //         return false;
    //     }

    //     $this->validation($description);

    //     $fund = $this->db->select('SELECT * FROM funds WHERE branch_id = ?', [$branchId])->fetch();

    //     if (!$fund) {
    //         $this->flashMessage('error', 'صندوق اصلی پیدا نشد!');
    //         return false;
    //     }

    //     $fundTotal = (float)$fund['total'];

    //     if ($fundTotal < $amount) {
    //         $this->flashMessage('error', 'مبلغ وارد شده از مجموع صندوق بیشتر است!');
    //         return false;
    //     }

    //     $centerFund = $this->db->select('SELECT * FROM center_fund WHERE branch_id = ?', [$branchId])->fetch();

    //     $yearMonth = $this->calendar->getYearMonth();
    //     $year      = $yearMonth['year'];
    //     $month     = $yearMonth['month'];

    //     $this->db->beginTransaction();

    //     try {

    //         $newFundTotal = $fundTotal - $amount;
    //         $transferred = $fund['transferred'] + $amount;

    //         $this->db->update('funds', $fund['id'], ['total', 'transferred'], [$newFundTotal, $transferred]);

    //         if ($centerFund) {

    //             $newCenterValue = $centerFund['amount'] + $amount;

    //             $this->db->update(
    //                 'center_fund',
    //                 $centerFund['id'],
    //                 ['amount'],
    //                 [$newCenterValue]
    //             );
    //         } else {

    //             $add = [
    //                 'branch_id' => $branchId,
    //                 'amount'    => $amount,
    //                 'year'      => $year,
    //                 'who_it'    => $request['who_it'],
    //             ];

    //             $this->db->insert('center_fund', array_keys($add), $add);
    //         }

    //         $transaction = [
    //             'branch_id'     => $branchId,
    //             'amount'        => $amount,
    //             'type'          => 1,
    //             'date'          => $request['date'],
    //             'imported_from' => 'mainFund',
    //             'description'   => $description,
    //             'year'          => $year,
    //             'month'         => $month,
    //             'who_it'        => $request['who_it'],
    //         ];

    //         $this->db->insert('center_fund_transactions', array_keys($transaction), $transaction);

    //         $this->db->commit();

    //         $this->flashMessage('success', _success);
    //         return true;
    //     } catch (\Exception $e) {


    //         $this->db->rollBack();

    //         $this->flashMessage('error', 'خطا در انجام عملیات انتقال پول!');
    //         return false;
    //     }
    // }
}
