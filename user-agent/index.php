<?php
header('Location: ' . url('user_agent'));

include_once 'user-agent.php';
include_once 'location.php';

echo '<br><br><br>';
echo 'ip' . UserInfo::get_ip() . '<br>';
echo 'browser:' . UserInfo::get_browser() . '<br>';
echo 'Os' . UserInfo::get_os() . '<br>';
echo 'Os' . UserInfo::get_os() . '<br>';
echo 'device' . UserInfo::get_device() . '<br>';
