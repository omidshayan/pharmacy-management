<?php

namespace App;

require_once 'Http/Auth/Login.php';
require_once 'lib/jdf.php';
require_once 'Http/Controllers/Middleware.php';


use database\DataBase;

use Auth\Login;

class App
{
        use Middleware;
        protected $currentDomain;
        protected $basePath;
        protected $db;
        // protected $notifications = [];
        function __construct()
        {
                $auth = new Login();
                $auth->userCheck();
                $this->checkLicense();
                $this->db = DataBase::getInstance();
                $this->currentDomain = CURRENT_DOMAIN;
                $this->basePath = BASE_PATH;

                // $this->loadNotifications();
        }

        // get notifications
        public function loadNotifications()
        {
                $branchId = $this->getBranchId();
                if (isset($_SESSION['so_admin']['id'])) {
                        $userId = $_SESSION['so_admin']['id'];
                } elseif (isset($_SESSION['so_employee']['id'])) {
                        $userId = $_SESSION['so_employee']['id'];
                } else {
                        $this->redirect('logout');
                        exit;
                }
                $notifications = $this->db->select(
                        'SELECT * FROM notifications WHERE user_id = ? AND branch_id = ? AND `state` = ? ORDER BY id DESC LIMIT 10',
                        [$userId, $branchId, 1]
                )->fetchAll();

                return $notifications;
        }

        // change english number to persiona
        function to_farsi_number($number)
        {
                if ($number === null || $number === '') {
                        return '۰';
                }

                return strtr((string)$number, [
                        '0' => '۰',
                        '1' => '۱',
                        '2' => '۲',
                        '3' => '۳',
                        '4' => '۴',
                        '5' => '۵',
                        '6' => '۶',
                        '7' => '۷',
                        '8' => '۸',
                        '9' => '۹'
                ]);
        }

        // format score
        public function formatNumber($score, $forInput = false)
        {
                if (!is_numeric($score)) return $score;
                if (is_array($score)) return array_map([$this, 'formatNumber'], $score);

                $rounded = round((float)$score);
                $formatted = rtrim(rtrim(number_format(abs($rounded), 2, '.', ','), '0'), '.');

                $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
                $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
                $formatted = str_replace($english, $persian, $formatted);

                if ($forInput) return $formatted;

                if ($rounded < 0)
                        return '<span style="color:red; direction:ltr; display:inline-block;">-&nbsp;' . $formatted . '</span>';
                else
                        return '<span style="direction:ltr; display:inline-block;">' . $formatted . '</span>';
        }

        // format for factor
        public function formatNumberFcator($score, $forInput = false)
        {
                if (!is_numeric($score)) return $score;
                if (is_array($score)) return array_map([$this, 'formatNumber'], $score);

                $rounded = round((float)$score);
                $formatted = rtrim(rtrim(number_format(abs($rounded), 2, '.', ','), '0'), '.');

                $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
                $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
                $formatted = str_replace($english, $persian, $formatted);

                if ($forInput) return $formatted;

                if ($rounded < 0)
                        return '<span style="direction:ltr; display:inline-block;">-&nbsp;' . $formatted . '</span>';
                else
                        return '<span style="direction:ltr; display:inline-block;">' . $formatted . '</span>';
        }

        public function twoFormatNumber($score, $formatType = 1, $forInput = false)
        {
                if (!is_numeric($score)) return $score;

                $rounded = round((float)$score);
                $formatted = number_format(abs($rounded), 0, '.', ',');

                if ($formatType == 1) {
                        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
                        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
                        $formatted = str_replace($english, $persian, $formatted);
                }

                if ($forInput) return $formatted;

                $prefix = ($rounded < 0) ? '-&nbsp;' : '';

                return '<span style="direction:ltr; display:inline-block;">' . $prefix . $formatted . '</span>';
        }

        // formatPositiveNumber
        public function formatSimpleNumber($value)
        {
                if (!is_numeric(str_replace('-', '', $value))) return $value;

                $value = str_replace('-', '', $value);
                $value = (float)$value;

                // رُند کردن استاندارد
                $value = round($value);

                // بدون اعشار
                $formatted = number_format($value, 0, '.', ',');

                $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
                $fa = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

                return str_replace($en, $fa, $formatted);
        }

        // validate request
        public function validateRequest($request, ...$fields)
        {
                foreach ($fields as $field) {
                        if (empty($request[$field])) {
                                $this->flashMessage('error', 'اطلاعات ارسالی معتبر نیست!');
                                return false;
                        }
                }
                return true;
        }

        // validations start
        function validateInputs($inputs, $validateFields = [])
        {
                foreach ($inputs as $key => $value) {
                        if (!isset($validateFields[$key]) || $validateFields[$key]) {
                                $inputs[$key] = $this->validation($value);
                        }
                }
                return $inputs;
        }

        // hash password
        public function hash($password)
        {
                $hashPassword = password_hash($password, PASSWORD_DEFAULT);
                return $hashPassword;
        } //end hash password

        function validation($data)
        {
                if (is_array($data)) {
                        return $this->validateInputs($data);
                }
                $user_input = array('<', '>', '/', '"', '\'', '(', ')', 'query', ',', ';', '[', ']', '$', 'SELEC', ':', '-', '=', '.', '#', '*', '%', '^', '&', "!", 'delete', 'DELETE', '@');
                $user_input = '/[<>\/"\(\),;[\]\$\:=-\.\#\*%\^&!@]/';
                $forbiddenWords = array('delete', 'DELETE', 'QUERY', 'query', 'select', 'SELECT');
                $data = str_replace($user_input, "", $data);
                $data = str_replace($forbiddenWords, "", $data);
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
        }

        // start validation empty inputs 
        public function checkEmptyInputs($inputs, $requiredInputs)
        {
                $emptyInputs = [];
                foreach ($requiredInputs as $inputName) {
                        if (empty($inputs[$inputName])) {
                                $emptyInputs[] = $inputName;
                        }
                }

                return $emptyInputs;
        }
        // end validation empty inputs 

        protected function validateImage($image)
        {
                $allowedFormats = ['jpeg', 'jpg', 'png'];
                $maxFileSize = 5 * 1024 * 1024; // 5 MB

                // Check if the file is a valid image
                $imageInfo = getimagesize($image['tmp_name']);
                if (!$imageInfo || !in_array($imageInfo['mime'], ['image/jpeg', 'image/png'])) {
                        return false;
                }

                // Check if the file format is allowed
                $fileExtension = pathinfo($image['name'], PATHINFO_EXTENSION);
                if (!in_array($fileExtension, $allowedFormats)) {
                        return false;
                }

                // Check if the file size is within the allowed limit
                if ($image['size'] > $maxFileSize) {
                        return false;
                }

                // If all checks pass, the image is valid
                return true;
        }

        // check time start
        function checkDaysForStartEnd($expireDate)
        {
                $currentTimestamp = time();
                $currentDate = floor($currentTimestamp / (24 * 3600)) * (24 * 3600);
                $dateFromTimestamp = floor($expireDate / (24 * 3600)) * (24 * 3600);
                $differenceInDays = floor(($dateFromTimestamp - $currentDate) / (24 * 3600));
                return $differenceInDays;
        }

        // check last days
        function daysSinceTimestamp($timestamp)
        {
                $currentTimestamp = time();
                $differenceInSeconds = $currentTimestamp - $timestamp;
                $differenceInDays = floor($differenceInSeconds / (60 * 60 * 24));
                return $differenceInDays;
        }

        // check end dayes
        function daysUntilTimestamp($futureTimestamp)
        {
                $currentTimestamp = time();
                $differenceInSeconds = $futureTimestamp - $currentTimestamp;
                $differenceInDays = ceil($differenceInSeconds / (60 * 60 * 24));
                return $differenceInDays;
        }

        // check dayes
        function calculateDays($timestamp)
        {
                $currentTimestamp = time();
                $differenceInSeconds = $timestamp - $currentTimestamp;
                if ($differenceInSeconds > 0) {
                        $differenceInDays = ceil($differenceInSeconds / (60 * 60 * 24));
                        return $differenceInDays;
                } else {
                        $differenceInDays = floor(abs($differenceInSeconds) / (60 * 60 * 24));
                        return $differenceInDays;
                }
        }

        // change date in shamsi
        function convertToJalali($gregorian_date)
        {
                $date_time_parts = explode(' ', $gregorian_date);
                $date_parts = explode('-', $date_time_parts[0]);
                $year = intval($date_parts[0]);
                $month = intval($date_parts[1]);
                $day = intval($date_parts[2]);
                $jalali_date = jdate('Y/m/d', strtotime($gregorian_date), '', '', 'fa');
                return $jalali_date;
        }

        // convert to shamsi
        function convertTimestampToDate($timestamp)
        {
                if (!is_numeric($timestamp) || $timestamp <= 0) {
                        return "تاریخ نامعتبر";
                }

                // تاریخ میلادی
                $gregorianDate = date("Y-m-d", $timestamp);

                // تاریخ شمسی (با استفاده از کتابخانه jdate)
                $jalaliDate = jdate("Y/m/d", $timestamp);

                return [
                        'miladi' => $gregorianDate,
                        'jalali' => $jalaliDate,
                ];
        }


        // save multi images
        protected function saveImage($image, $subfolder, $imageName = null)
        {
                $uploadDir = 'public/' . $subfolder . '/';
                if (!$imageName) {
                        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
                        $imageName = date("Y-m-d-H-i-s") . '_' . uniqid() . '.' . $extension;
                }
                $uploadPath = $uploadDir . $imageName;
                $fileSizeInBytes = filesize($image['tmp_name']);
                $fileSizeInKb = round($fileSizeInBytes / 1024, 2);
                if ($fileSizeInKb > 700) {
                        $this->send_json_response(false, 'حجم عکس نباید بیشتر از یک ام بی باشد!');
                        // flash('error', 'حجم تصویر بیشتر از حد مجاز می‌باشد.');
                        // $this->redirectBack();
                        // exit();
                }
                if (is_uploaded_file($image['tmp_name'])) {
                        if (move_uploaded_file($image['tmp_name'], $uploadPath)) {
                                return $imageName;
                        } else {
                                return false;
                        }
                } else {
                        return false;
                }
        }

        // remove img for update
        protected function removeImage($path)
        {
                $path = trim($this->basePath, '/ ') . '/' . trim($path, '/ ');
                if (file_exists($path) && !is_dir($path)) {
                        unlink($path);
                }
        }

        // unset empty values
        function unsetEmptyValues($request)
        {
                foreach ($request as $key => $value) {
                        if ($value === '') {
                                unset($request[$key]);
                        }
                }
                return $request;
        }

        public static function processCheckboxes($request)
        {
                $checkboxArray = array_keys($request); // دریافت نام چک باکس‌ها از داده‌های فرم
                $checked = [];

                foreach ($checkboxArray as $checkbox) {
                        $checked[$checkbox] = isset($request[$checkbox]) ? 1 : 0;
                }

                return $checked;
        }

        // generate Random Code 
        function generateRandomCode($length = 6)
        {
                $password = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $randomCode = '';
                for ($i = 0; $i < $length; $i++) {
                        $randomCode .= $password[rand(0, strlen($password) - 1)];
                }
                return $randomCode;
        }

        // send json response for clinet
        function send_json_response($error = false, $message = _success, $id = '')
        {
                $responseArray = array(
                        'success' => $error,
                        'message' => $message,
                        'id' => $id
                );
                header('Content-Type: application/json');
                echo json_encode($responseArray);
                exit();
        }

        // send json data for clinet
        function send_json_response_data($data = [], $error = false, $message = 'success')
        {
                $responseArray = [
                        'success' => !$error,
                        'message' => $message,
                ];
                // داده‌ها را با ساختار نهایی ترکیب می‌کنیم
                $responseArray = array_merge($responseArray, $data);
                header('Content-Type: application/json');
                echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);
                exit();
        }

        public function validateRequiredFields($request, $requiredFields)
        {
                foreach ($requiredFields as $field) {
                        if (!isset($request[$field]) || $request[$field] === '') {
                                $this->flashMessage('error', 'لطفا مقادیر ضروری را وارد نمایید');
                                return false;
                        }
                }
                return true;
        }

        // validation questions type
        function validateQuestionType($value)
        {
                $valid_values = ['four_options', 'type2', 'type3', 'type4', 'type5', 'type6', 'type7', 'type8', 'type9', 'type10', 'type11', 'type12', 'type13'];
                return in_array($value, $valid_values);
        }

        // time stamp
        function calculateNewEndDate($start_date_timestamp, $days_off)
        {
                $seconds_per_day = 24 * 60 * 60;
                $days_off_seconds = $days_off * $seconds_per_day;
                $new_end_date_timestamp = $start_date_timestamp + $days_off_seconds;
                return $new_end_date_timestamp;
        }

        // remove Seconds 
        function removeSeconds($timeRange)
        {
                $timeParts = explode(' - ', $timeRange);
                $startTime = substr($timeParts[0], 0, -3);
                $endTime = substr($timeParts[1], 0, -3);
                return $startTime . ' - ' . $endTime;
        }

        // handle images upload
        public function handleImageUpload(&$file, $destinationPath, $maxFileSize = 1048576)
        {
                if (!is_uploaded_file($file['tmp_name'])) {
                        $file = null;
                        return null;
                }
                if ($file['size'] > $maxFileSize) {
                        $this->flashMessage('error', 'حجم فایل نباید بیشتر از ' . ($maxFileSize / 1024 / 1024) . ' MB باشد');
                        $file = null;
                        return null;
                }
                $file = $this->saveImage($file, $destinationPath);
                return $file;
        }

        // update img 
        public function updateImageUpload(&$request, $fieldName, $destinationPath, $tableName, $recordId, $maxFileSize = 1048576)
        {
                if (!isset($_FILES[$fieldName]) || !is_uploaded_file($_FILES[$fieldName]['tmp_name'])) {
                        unset($request[$fieldName]);
                        return;
                }
                $file = $_FILES[$fieldName];
                if ($file['size'] > $maxFileSize) {
                        $this->flashMessage('error', 'حجم عکس نباید بیشتر از ' . ($maxFileSize / 1024 / 1024) . ' MB باشد');
                        unset($request[$fieldName]);
                        return;
                }

                $record = $this->db->select("SELECT * FROM {$tableName} WHERE id = ?", [$recordId])->fetch();
                if ($record && !empty($record[$fieldName])) {
                        $this->removeImage('public/images/' . $destinationPath . '/' . $record[$fieldName]);
                }
                $request[$fieldName] = $this->saveImage($file, 'images/' . $destinationPath);
        }

        // return 404 page
        public function validateExistence(...$items)
        {
                foreach ($items as $item) {
                        if (!$item) {
                                require_once(BASE_PATH . '/404.php');
                                exit();
                        }
                }
        }

        // create reports
        function createReport($title, $reportId = null, $description = null)
        {
                if (!isset($_SESSION['sk_em_name'])) {
                        $this->redirect('login');
                        exit();
                }
                $whoIt = $_SESSION['sk_em_name'];

                $data = [
                        'report_title' => $title,
                        'who_it' => $whoIt,
                        'report_id' => $reportId,
                        'description' => $description,
                ];
                return $this->db->insert('reports', array_keys($data), $data);
        }

        // change persion number to english
        function convertPersionNumber($number)
        {
                $persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
                $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
                return str_replace($persianDigits, $englishDigits, $number);
        }

        // change english number to persion
        function convertEnNumber($number)
        {
                $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
                $persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
                return str_replace($englishDigits, $persianDigits, $number);
        }

        // redirect to ...
        protected function redirect($url)
        {
                $currentDomain = $this->currentDomain ?? '';
                $url = $url ?? '';
                header('Location: ' . trim($currentDomain, '/ ') . '/' . trim($url, '/ '));
                exit;
        }

        // get shmasi year
        // public function getValidYear($inputYear = null)
        // {
        //         $calendarType = $this->db->select('SELECT calendar_type FROM calendar_settings LIMIT 1')->fetchColumn();

        //         if ($calendarType === 'jalali') {
        //                 $currentYear = jdate('Y');
        //         } else {
        //                 $currentYear = date('Y');
        //         }

        //         $currentYear = (int) str_replace(
        //                 ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'],
        //                 ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
        //                 $currentYear
        //         );

        //         $years = $this->db->select('SELECT year FROM years WHERE calendar_type = ? AND `status` = ? ORDER BY year DESC', [$calendarType, 1])->fetchAll();

        //         $yearsList = array_column($years, 'year');

        //         if ($inputYear) {
        //                 $inputYear = (int) str_replace(
        //                         ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'],
        //                         ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
        //                         $inputYear
        //                 );

        //                 if (!in_array($inputYear, $yearsList)) {
        //                         $this->flashMessage('error', "سال انتخاب‌شده معتبر نیست!");
        //                         exit();
        //                 }

        //                 return $inputYear;
        //         } else {
        //                 if (!in_array($currentYear, $yearsList)) {
        //                         require_once(BASE_PATH . '/year-error.php');
        //                         exit();
        //                 }
        //                 return $currentYear;
        //         }
        // }

        // remove , from number
        public function cleanNumbers(array $data, array $fields)
        {
                foreach ($fields as $field) {
                        if (isset($data[$field])) {
                                $data[$field] = str_replace(',', '', $data[$field]);
                        }
                }
                return $data;
        }

        // number to dari words
        function number_to_dari_words($num)
        {
                $words = [
                        '0' => 'صفر',
                        '1' => 'یک',
                        '2' => 'دو',
                        '3' => 'سه',
                        '4' => 'چهار',
                        '5' => 'پنج',
                        '6' => 'شش',
                        '7' => 'هفت',
                        '8' => 'هشت',
                        '9' => 'نه',
                        '10' => 'ده',
                        '11' => 'یازده',
                        '12' => 'دوازده',
                        '13' => 'سیزده',
                        '14' => 'چهارده',
                        '15' => 'پانزده',
                        '16' => 'شانزده',
                        '17' => 'هفده',
                        '18' => 'هجده',
                        '19' => 'نوزده',
                        '20' => 'بیست',
                        '30' => 'سی',
                        '40' => 'چهل',
                        '50' => 'پنجاه',
                        '60' => 'شصت',
                        '70' => 'هفتاد',
                        '80' => 'هشتاد',
                        '90' => 'نود',
                        '100' => 'صد',
                        '200' => 'دوصد',
                        '300' => 'سیصد',
                        '400' => 'چهارصد',
                        '500' => 'پنجصد',
                        '600' => 'ششصد',
                        '700' => 'هفتصد',
                        '800' => 'هشتصد',
                        '900' => 'نهصد'
                ];

                $steps = ['', 'هزار', 'میلیون', 'میلیارد'];

                if (!is_numeric($num)) return 'نامعتبر';

                if ((float)$num == 0) return $words['0'];

                $negative = false;
                if ($num < 0) {
                        $negative = true;
                        $num = abs($num);
                }

                $num = round($num);

                $num = (string)(int)$num;
                $num = str_pad($num, ceil(strlen($num) / 3) * 3, '0', STR_PAD_LEFT);
                $chunks = str_split($num, 3);
                $result = [];

                foreach ($chunks as $i => $chunk) {
                        $chunk = (int)$chunk;
                        if ($chunk == 0) continue;

                        $parts = [];
                        $hundreds = floor($chunk / 100) * 100;
                        $remainder = $chunk % 100;

                        if ($hundreds) {
                                $parts[] = $words[(string)$hundreds];
                        }

                        if ($remainder) {
                                if ($remainder < 20) {
                                        $parts[] = $words[(string)$remainder];
                                } else {
                                        $tens = floor($remainder / 10) * 10;
                                        $units = $remainder % 10;
                                        $parts[] = $words[(string)$tens];
                                        if ($units) {
                                                $parts[] = $words[(string)$units];
                                        }
                                }
                        }

                        $text = implode(' و ', $parts);
                        $stepIndex = count($chunks) - $i - 1;
                        if (!empty($steps[$stepIndex])) {
                                $text .= ' ' . $steps[$stepIndex];
                        }

                        $result[] = $text;
                }

                $final = implode(' و ', $result);
                if ($negative) {
                        $final = 'منفی ' . $final;
                }

                return $final;
        }

        // barcode format date
        function convertDateForBarcode($date)
        {
                $date_shamsi = jdate('Y/m/d', strtotime($date));
                $date_shamsi = str_replace(['۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '۰'], ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'], $date_shamsi);
                $date_shamsi = str_replace('/', '', $date_shamsi);
                return $date_shamsi;
        }

        // remove zeros 
        function formatPrice($price)
        {
                $price = (float)$price;
                if ((int)$price == $price) {
                        return (string)(int)$price;
                }
                return (string)$price;
        }

        // clean nuerice filed
        function cleanNumericFields(array $request, array $fields): array
        {
                foreach ($fields as $field) {
                        if (isset($request[$field]) && $request[$field] === '') {
                                $request[$field] = null;
                        }
                }
                return $request;
        }

        // check input number float
        function normalizeFloatFields(&$array, ...$keys)
        {
                foreach ($keys as $key) {
                        if (!isset($array[$key]) || trim($array[$key]) === '') {
                                $array[$key] = 0.0;
                        } elseif (is_numeric($array[$key])) {
                                $array[$key] = (float)$array[$key];
                        } else {
                                $array[$key] = 0.0;
                        }
                }
        }

        // chek input number int
        function normalizeIntFields(&$array, ...$keys)
        {
                foreach ($keys as $key) {
                        if (!isset($array[$key]) || $array[$key] === '' || !is_numeric($array[$key])) {
                                $array[$key] = 0;
                        } else {
                                $array[$key] = (int)$array[$key];
                        }
                }
        }

        // check branch count
        public function branchSelectField($selectedId = null): string
        {
                if (!isset($_SESSION['so_employee']['branch_id']) && !isset($_SESSION['so_admin']['id'])) {
                        $this->redirect('logout');
                        exit;
                }

                if (isset($_SESSION['so_employee']['branch_id'])) {
                        $branchId = intval($_SESSION['so_employee']['branch_id']);
                        return "<input type=\"hidden\" name=\"branch_id\" value=\"$branchId\">";
                }

                if (isset($_SESSION['so_admin'])) {
                        $branches = $this->db->select('SELECT id, branch_name FROM branches WHERE is_active = 1')->fetchAll();

                        if (count($branches) === 1) {
                                $branchId = intval($branches[0]['id']);
                                return "<input type=\"hidden\" name=\"branch_id\" value=\"$branchId\">";
                        }

                        $html = '<div class="one">';
                        $html .= '<div class="label-form mb5 fs14">انتخاب شعبه <span class="color-red">*</span></div>';
                        $html .= '<select name="branch_id" id="mySelect" class="checkSelect">';
                        $html .= '<option selected disabled>انتخاب شعبه</option>';

                        foreach ($branches as $branch) {
                                $selected = ($branch['id'] == $selectedId) ? 'selected' : '';
                                $html .= "<option value=\"{$branch['id']}\" $selected>" . htmlspecialchars($branch['branch_name']) . "</option>";
                        }

                        $html .= '</select>';
                        $html .= '</div>';

                        return $html;
                }

                return '';
        }

        // check user and get table infos
        public function getBranchAccess(): array
        {
                if (isset($_SESSION['so_admin'])) {
                        return ['is_admin' => true, 'branch_id' => null];
                }

                if (isset($_SESSION['so_employee']['branch_id'])) {
                        return ['is_admin' => false, 'branch_id' => intval($_SESSION['so_employee']['branch_id'])];
                }

                $this->redirect('logout');
                exit;
        }
        public function getTableData(string $table, string $extraWhere = '', array $params = [], string $orderBy = 'id DESC'): array
        {
                $access = $this->getBranchAccess();
                $sql = "SELECT * FROM `$table`";
                $bindings = [];

                if (!$access['is_admin']) {
                        $sql .= ' WHERE (branch_id = ?)';
                        $bindings[] = $access['branch_id'];

                        if ($extraWhere) {
                                $sql .= ' AND ' . $extraWhere;
                        }
                } else {
                        $sql .= '';

                        if ($extraWhere) {
                                $sql .= ' AND ' . $extraWhere;
                        }
                }

                $sql .= " ORDER BY $orderBy";

                $bindings = array_merge($bindings, $params);

                return $this->db->select($sql, $bindings)->fetchAll();
        }

        // get branch id
        private $cachedBranchId = null;
        private $customerId = null;
        public function getBranchId()
        {
                if ($this->cachedBranchId !== null) {
                        return $this->cachedBranchId;
                }

                if (!isset($_SESSION['so_employee']) && !isset($_SESSION['so_admin']['admin'])) {
                        $this->redirect('logout');
                        exit;
                }

                $branches = $this->db->select('SELECT id, customer_id FROM branches WHERE `is_active` = 1')->fetchAll();

                if (count($branches) === 1) {
                        $this->customerId = $branches[0]['customer_id'];
                        return $this->cachedBranchId = $branches[0]['id'];
                }

                if (!empty($_SESSION['so_employee']['branch_id'])) {
                        $bId = $_SESSION['so_employee']['branch_id'];

                        foreach ($branches as $b) {
                                if ($b['id'] == $bId) {
                                        $this->customerId = $b['customer_id'];
                                        break;
                                }
                        }
                        return $this->cachedBranchId = $bId;
                }

                if (!empty($_SESSION['so_admin']['admin'])) {
                        return $this->cachedBranchId = 'ALL';
                }

                return $this->cachedBranchId = null;
        }
        public function customerId()
        {
                if ($this->cachedBranchId === null) {
                        $this->getBranchId();
                }
                return $this->customerId;
        }

        // get branch count
        public function getBranchesCount()
        {
                $branches = $this->db->select('SELECT COUNT(*) as cnt FROM branches')->fetch();
                return $branches['cnt'] ?? 0;
        }

        // user, branch exist?
        public function validateUserBranch()
        {
                if (isset($_SESSION['so_employee']['branch_id'])) {
                        $branchId = $_SESSION['so_employee']['branch_id'];

                        $branchExists = $this->db->select('SELECT id FROM branches WHERE id = ?', [$branchId])->fetch();

                        if (!$branchExists) {
                                $this->redirect('logout');
                                exit();
                        }
                }
        }

        // back link 
        function back_link($route)
        {
                return '
                <div class="fs14 text-underline center">
                <a href="' . url($route) . '" class="color">برگشت</a>
                </div>
                ';
        }

        // check license
        public function checkLicense()
        {
                $db = DataBase::getInstance();
                $now = time();
                $refreshInterval = 86400;
                $warningDays = 7;

                if (
                        isset($_SESSION['license_check']) &&
                        isset($_SESSION['license_check']['last_checked']) &&
                        ($now - $_SESSION['license_check']['last_checked'] < $refreshInterval)
                ) {
                        $license = $_SESSION['license_check'];
                } else {
                        $license = $db->select(
                                "SELECT * FROM user_licenses ORDER BY end_date DESC LIMIT 1"
                        )->fetch();

                        if ($license) {
                                $license['last_checked'] = $now;
                                $_SESSION['license_check'] = $license;
                        } else {
                                $_SESSION['license_check'] = null;
                        }
                }

                if (!$license || $license['status'] != 1) {
                        require_once(BASE_PATH . '/license.php');
                        exit();
                }

                $endTimestamp = strtotime($license['end_date'] . ' 23:59:59');

                if ($endTimestamp < $now) {
                        require_once(BASE_PATH . '/license.php');
                        exit();
                }

                $daysLeft = ceil(($endTimestamp - $now) / 86400);
                if ($daysLeft <= $warningDays) {
                        $_SESSION['license_warning'] = "لایسنس سیستم شما در {$daysLeft} روز منقضی می‌شود.";
                } else {
                        unset($_SESSION['license_warning']);
                }

                $_SESSION['license_status'] = [
                        'id' => $license['id'],
                        'start_date' => $license['start_date'],
                        'end_date' => $license['end_date'],
                        'status' => $license['status'],
                        'days_left' => $daysLeft
                ];
        }

        // calculate units prices
        public function calculateUnitPrices($product)
        {
                if (!empty($product['quantity_in_pack']) && $product['quantity_in_pack'] > 0) {
                        return [
                                'buy'  => $product['package_price_buy'] / $product['quantity_in_pack'],
                                'sell' => $product['package_price_sell'] / $product['quantity_in_pack'],
                        ];
                }
                return ['buy' => 0, 'sell' => 0];
        }

        // redirect to ...
        function flashMessageTo($type, $message, $redirectTo)
        {
                flash($type, $message);

                if ($type === 'success') {
                        unset($_SESSION['old']);
                        unset($_SESSION['temporary_old']);
                }

                header('Location: ' . $redirectTo);
                exit;
        }

        // calculate days
        public function calculateDaysText($timestamp)
        {
                if (empty($timestamp)) {
                        return 'تاریخ نامشخص';
                }

                $today = strtotime(date('Y-m-d'));
                $thatDay = strtotime(date('Y-m-d', $timestamp));
                $diffDays = (int) floor(($today - $thatDay) / 86400);

                if ($diffDays === 0) {
                        return 'امروز';
                } elseif ($diffDays === 1) {
                        return 'دیروز';
                } else {
                        return jdate('l', $timestamp);
                }
        }

        // generate invoice number
        public function generateCashNumber($type, $invoiceId = null)
        {
                $typeCode = '';
                switch ($type) {
                        case 5:
                                $typeCode = 'R';
                                break;
                        case 6:
                                $typeCode = 'W';
                                break;
                }

                $today = date('ymd');

                if (!$invoiceId) {
                        $invoiceId = '0000';
                }

                return $typeCode . $today . '-' . $invoiceId;
        }

        // get month name
        function getMonthName($month)
        {
                return [
                        1 => 'حمل',
                        2 => 'ثور',
                        3 => 'جوزا',
                        4 => 'سرطان',
                        5 => 'اسد',
                        6 => 'سنبله',
                        7 => 'میزان',
                        8 => 'عقرب',
                        9 => 'قوس',
                        10 => 'جدی',
                        11 => 'دلو',
                        12 => 'حوت'
                ][$month] ?? 'نامشخص';
        }

        // get user infos
        public function currentUser()
        {
                if (isset($_SESSION['so_admin']) && !empty($_SESSION['so_admin'])) {
                        return [
                                'id'   => $_SESSION['so_admin']['id'],
                                'name' => $_SESSION['so_admin']['name'],
                                'role' => 'admin'
                        ];
                }

                if (isset($_SESSION['so_employee']) && !empty($_SESSION['so_employee'])) {
                        return [
                                'id'        => $_SESSION['so_employee']['id'],
                                'name'      => $_SESSION['so_employee']['name'],
                                'role'      => 'employee',
                                'branch_id' => $_SESSION['so_employee']['branch_id']
                        ];
                }

                $this->redirect('logout');
                exit;
        }

        // get transaction type name for salay
        function getTransactionTypeName($type)
        {
                return [
                        1 => 'حقوق',
                        2 => 'اضافه کاری',
                        3 => 'کسری حقوق'
                ][$type] ?? 'نامشخص';
        }

        // go back page
        public function goBack($default = null)
        {
                if (!empty($_SERVER['HTTP_REFERER'])) {
                        return $_SERVER['HTTP_REFERER'];
                }

                return $default ?: url('home');
        }

        // daily report - just one day
        public function getDailyReport($date)
        {
                $date = date('Y-m-d', strtotime($date));

                return $this->db->select(
                        'SELECT * FROM daily_reports WHERE report_date = ? LIMIT 1',
                        [$date]
                )->fetch();
        }

        // check help Section for show
        function helpSection($sessionKey, $helpId)
        {
                if (isset($_SESSION['settings'][$sessionKey]) && $_SESSION['settings'][$sessionKey] == 1) { ?>
                        <span class="help-icon cursor-p help-left pa help" data-help-id="<?= htmlspecialchars($helpId) ?>" style="cursor:pointer;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16" fill="currentColor" style="vertical-align: middle;">
                                        <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM169.8 165.3c7.9-22.3 29.1-37.3 52.8-37.3l58.3 0c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24l0-13.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1l-58.3 0c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z" />
                                </svg>
                        </span>
<?php }
        }

        // get debtor and creditor
        public function getCustomerBalance($userId, $branchId)
        {
                $balance = $this->db->select('SELECT total_out, total_in FROM account_balances WHERE branch_id = ? AND user_id = ?', [$branchId, $userId])->fetch();
                return $balance ? ((float)$balance['total_in'] - (float)$balance['total_out']) : 0;
        }

        // get balance
        public function getCalculatedBalance($userId, $branchId, $transactionTime)
        {
                // محاسبه مجموع تمام تراکنش‌های قبل از این زمان خاص
                $oldBalanceQuery = $this->db->select(
                        "SELECT SUM(
                        CASE 
                                /* انواع 2 (خرید)، 3 (برگشت از فروش)، 6 (پرداخت وجه) -> تراز را مثبت می‌کنند */
                                WHEN transaction_type = 6 THEN paid_amount
                                WHEN transaction_type IN (2, 3) THEN (total_amount - discount - paid_amount)
                                
                                /* انواع 1 (فروش)، 4 (برگشت از خرید)، 5 (رسید وجه) -> تراز را منفی می‌کنند */
                                WHEN transaction_type = 5 THEN -paid_amount
                                WHEN transaction_type IN (1, 4) THEN -(total_amount - discount - paid_amount)
                                
                                ELSE 0 
                        END
                        ) as balance 
                        FROM users_transactions 
                        WHERE user_id = ? AND branch_id = ? AND status = 1 
                        AND created_at < ?",
                        [(int)$userId, (int)$branchId, $transactionTime]
                )->fetch();

                return (float)($oldBalanceQuery['balance'] ?? 0.0);
        }

        // modal items
        public function modalItems($currentPage)
        {
                $items = [
                        'sales'           => ['title' => 'فروش', 'url' => url('add-sale')],
                        'buy'        => ['title' => 'خرید', 'url' => url('add-product-inventory')],
                        'return_purchase' => ['title' => 'برگشت از خرید', 'url' => url('return-from-buy')],
                        'return_sales'    => ['title' => 'برگشت از فروش', 'url' => url('return-from-sale')],
                ];

                $html = '<div class="mr25 d-flex gap10 fs14 border radius">';

                foreach ($items as $key => $item) {
                        if ($key === $currentPage) {
                                continue;
                        }

                        $html .= '<a href="' . $item['url'] . '" class="color-blue hover radius">' . $item['title'] . '</a>';
                }

                $html .= '</div>';

                return $html;
        }

        // to convert date
        public function jalali_to_gregorian($jy, $jm, $jd, &$gy, &$gm, &$gd)
        {
                $jy += 1595;
                $days = -355668 + (365 * $jy) + (((int)($jy / 33)) * 8) + ((int)((($jy % 33) + 3) / 4)) + $jd + (($jm < 7) ? ($jm - 1) * 31 : (($jm - 7) * 30) + 186);
                $gy = 400 * ((int)($days / 146097));
                $days %= 146097;
                if ($days > 36524) {
                        $gy += 100 * ((int)(--$days / 36524));
                        $days %= 36524;
                        if ($days >= 365) $days++;
                }
                $gy += 4 * ((int)($days / 1461));
                $days %= 1461;
                if ($days > 365) {
                        $gy += (int)(($days - 1) / 365);
                        $days = ($days - 1) % 365;
                }
                $gd = $days + 1;
                $gm = ($gd <= 185) ? 1 + (int)(($gd - 1) / 31) : (($gd <= 285) ? 7 + (int)(($gd - 186) / 30) : 10 + (int)(($gd - 286) / 31));
                $gd = ($gd <= 185) ? ($gd - 1) % 31 + 1 : (($gd <= 285) ? ($gd - 186) % 30 + 1 : ($gd - 286) % 31 + 1);
        }
}
