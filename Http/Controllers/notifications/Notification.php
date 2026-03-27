<?php

namespace App;

class Notification extends App
{
    // notifications page
    public function notifications()
    {
        $this->middleware(true, true, 'general', true);

        $branchId = $this->getBranchId();
        $userId = $_SESSION['so_employee']['id'];

        $get_notifications = $this->db->select('SELECT * FROM notifications WHERE branch_id = ? AND user_id = ?  ORDER BY id DESC', [$branchId, $userId])->fetchAll();
        require_once(BASE_PATH . '/resources/views/app/notifications/notifications.php');
    }

    // notification page
    public function notification($id)
    {
        $this->middleware(true, true, 'general', true);
        $notification = $this->db->select('SELECT * FROM notifications WHERE id = ?', [$id])->fetch();

        if ($notification) {
            require_once(BASE_PATH . '/resources/views/app/notifications/notification.php');
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }
}
