<?php

namespace Models\Notification;

use App\App;

class Notification extends App
{
    // send notifications
    public function sendNotif($notifData)
    {
        // if (empty($notifData['user_id']) || (int)$notifData['user_id'] === 0) {
        //     $notifData['user_id'] = 1;
        // }

        $branchId = $this->getBranchId();

        $admins = $this->db->select(
            'SELECT id FROM employees WHERE notif = ? AND branch_id IN (?, ?)',
            [2, $branchId, 100]
        )->fetchAll();

        $notifs = [];

        switch ($notifData['type']) {
            case 1:
                $adminMsg =  'بِل فروش (شماره ' . $notifData['ref_id'] . ') ثبت شد.';
                $userMsg = 'بِل خرید (شماره ' . $notifData['ref_id'] . ') برای شما ثبت شد.';
                $title = 'بِل فروش';
                break;

            case 2:
                $adminMsg =  'بِل خرید (شماره ' . $notifData['ref_id'] . ') ثبت شد.';
                $userMsg = 'بِل فروش (شماره ' . $notifData['ref_id'] . ') به حساب شما ثبت شد.';
                $title = 'بِل خرید';
                break;

            case 3:
                $adminMsg =  'بِل برگشت از فروش (شماره ' . $notifData['ref_id'] . ') ثبت شد.';
                $userMsg = 'بِل برگشت از خرید (شماره ' . $notifData['ref_id'] . ') برای شما ثبت شد.';
                $title = 'برگشت از فروش';
                break;

            case 4:
                $adminMsg =  'بِل برگشت از خرید (شماره ' . $notifData['ref_id'] . ') ثبت شد.';
                $userMsg = 'بِل برگشت از فروش (شماره ' . $notifData['ref_id'] . ') برای شما ثبت شد.';
                $title = 'برگشت از خرید';
                break;

            case 5:
                $adminMsg =  'رسید پول ثبت شد';
                $userMsg = 'رسید پول به حساب شما ثبت شد';
                $title = 'رسید پول';
                break;

            case 6:
                $adminMsg =  'پرداخت پول ثبت شد';
                $userMsg = 'پرداخت پول به حساب شما ثبت شد';
                $title = 'پرداخت پول';
                break;

            case 7:
                $adminMsg =  'پرداخت معاش برای ' . $notifData['employee_name'] . ' ثبت شد.';
                $userMsg = 'پرداخت معاش  ' . $notifData['ref_id'] . ' برای شما ثبت شد.';
                $title = 'پرداخت معاش';
                break;

            case 8: // expense
                $adminMsg = ' یک مصرفی ثبت شد.';
                $title = 'ثبت مصرفی';
                break;

            default:
                $adminMsg = 'تراکنش جدید ثبت شد.';
                $userMsg = 'تراکنش جدید برای شما ثبت شد.';
        }

        foreach ($admins as $admin) {
            $notifs[] = [
                'branch_id' => $notifData['branch_id'],
                'user_id' => $admin['id'],
                'ref_id' => $notifData['ref_id'],
                'notif_type' => $notifData['type'],
                'msg' => $adminMsg,
                'title' => $title,
            ];
        }

        if (!empty($notifData['user_id']) && (int)$notifData['user_id'] > 1) {
            $notifs[] = [
                'branch_id' => $notifData['branch_id'],
                'user_id' => $notifData['user_id'],
                'ref_id' => $notifData['ref_id'],
                'notif_type' => $notifData['type'],
                'msg' => $userMsg,
                'title' => $title,
            ];
        }

        foreach ($notifs as $notif) {
            $this->db->insert('notifications', array_keys($notif), $notif);
        }
    }
}
