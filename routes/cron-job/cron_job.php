<?php
require_once 'Http/Controllers/cron-job/CronJob.php';

// exec cron job routes
uri('cron-job', 'App\CronJob', 'cronJob');
