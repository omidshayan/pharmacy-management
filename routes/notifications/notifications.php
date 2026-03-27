<?php
require_once 'Http/Controllers/notifications/Notification.php';

// notifications routes
uri('notifications', 'App\Notification', 'notifications');
uri('notification/{id}', 'App\Notification', 'notification');
