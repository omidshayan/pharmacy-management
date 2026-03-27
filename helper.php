<?php
//helpers functions

function protocol()
{
        return  stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
}

function currentDomain()
{
        return protocol() . $_SERVER['HTTP_HOST'];
}

function asset($src)
{
        $domain = trim(CURRENT_DOMAIN, '/ ');
        $src = $domain . '/' . trim($src, '/');
        return $src;
}

function url($url)
{
        $domain = trim(CURRENT_DOMAIN, '/ ');
        $url = $domain . '/' . trim($url, '/');
        return $url;
}

function currentUrl()
{
        return currentDomain() . $_SERVER['REQUEST_URI'];
}

// check settings for active
function feature($key)
{
        return ($_SESSION['settings'][$key] ?? 0) == 1;
}

function methodField()
{
        return $_SERVER['REQUEST_METHOD'];
}
global $flashMessage;
if (isset($_SESSION['flash_message'])) {
        $flashMessage = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
}

function flash($name, $value = null)
{
        if ($value === null) {
                global $flashMessage;
                $message = isset($flashMessage[$name]) ? $flashMessage[$name] : '';
                return $message;
        } else {
                $_SESSION['flash_message'][$name] = $value;
        }
}

spl_autoload_register(function ($className) {
        $path = BASE_PATH . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR;
        $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
        include $path . $className . '.php';
});

function jalaliData($date)
{
        return \Parsidev\Jalali\jdate::forge($date)->format('date');
}

function dd($var)
{
        echo '<pre>';
        var_dump($var);
        exit;
}

// check number and formating
function format_number($value, $decimals = 0, $decimal_separator = '.', $thousand_separator = ',')
{
        $formatted = number_format(abs($value), $decimals, $decimal_separator, $thousand_separator);
        $sign = $value < 0 ? '- ' : '';
        $class = $value < 0 ? ' class="color-red bold"' : '';
        return '<span dir="ltr"' . $class . '>' . $sign . $formatted . '</span>';
}

// old start
if (isset($_SESSION['old'])) {
        unset($_SESSION['temporary_old']);
}
if (isset($_SESSION['old'])) {
        $_SESSION['temporary_old'] = $_SESSION['old'];
        unset($_SESSION['old']);
}
$params = [];
$params = !isset($_GET) ? $params : array_merge($params, $_GET);
$params = !isset($_POST) ? $params : array_merge($params, $_POST);
$_SESSION['old'] = $params;
unset($params);
function old($name)
{
        if (isset($_SESSION['temporary_old'][$name])) {
                return $_SESSION['temporary_old'][$name];
        } else {
                return null;
        }
}
// old end

// check status 
function statusColor($status)
{
        switch ($status) {
                case 2:
                        return 'color-red';
                default:
                        return 'color-gray';
        }
}

// pagination
include_once('resources/paginate.php');

// google captcah
define('_data_sitekey', 'inter key');
define('_secret', 'inter secret');
