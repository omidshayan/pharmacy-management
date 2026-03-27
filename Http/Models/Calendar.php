<?php

namespace Models\Calendar;

use App\App;

class Calendar extends App
{

    // get calendar type
    public function getCalendarType()
    {
        return $calendarType = $this->db->select('SELECT calendar_type FROM calendar_settings LIMIT 1')->fetchColumn();
    }

    // get mouth and year for attendance
    public function getYearMonth($inputYear = null)
    {
        $calendarType = $this->getCalendarType();

        if ($calendarType === 'jalali') {
            $currentYear = jdate('Y');
            $currentMonth = jdate('m');
        } else {
            $currentYear = date('Y');
            $currentMonth = date('m');
        }

        $currentYear = $this->convertPersionNumber($currentYear);
        $currentMonth = $this->convertPersionNumber($currentMonth);


        $years = $this->db->select('SELECT year FROM years WHERE calendar_type = ? AND `status` = ? ORDER BY year DESC', [$calendarType, 1])->fetchAll();
        $yearsList = array_column($years, 'year');

        if ($inputYear) {
            $inputYear = $this->convertPersionNumber($inputYear);

            if (!in_array($inputYear, $yearsList)) {
                $this->flashMessage('error', "سال انتخاب‌شده معتبر نیست!");
                exit();
            }

            return ['year' => $inputYear, 'month' => $currentMonth];
        } else {
            if (!in_array($currentYear, $yearsList)) {
                require_once(BASE_PATH . '/year-error.php');
                exit();
            }

            return ['year' => $currentYear, 'month' => $currentMonth];
        }
    }

    // get shmasi year
    // public function getValidYear($inputYear = null)
    // {

    //     $calendarType = $this->getCalendarType();
    //     if ($calendarType === 'jalali') {
    //         $currentYear = jdate('Y');
    //     } else {
    //         $currentYear = date('Y');
    //     }

    //     $currentYear = $this->convertPersionNumber($currentYear);

    //     $years = $this->db->select('SELECT year FROM years WHERE calendar_type = ? AND `status` = ? ORDER BY year DESC', [$calendarType, 1])->fetchAll();

    //     $yearsList = array_column($years, 'year');

    //     if ($inputYear) {
    //         $inputYear = $this->convertPersionNumber($inputYear);

    //         if (!in_array($inputYear, $yearsList)) {
    //             $this->flashMessage('error', "سال انتخاب‌شده معتبر نیست!");
    //             exit();
    //         }

    //         return $inputYear;
    //     } else {
    //         if (!in_array($currentYear, $yearsList)) {
    //             require_once(BASE_PATH . '/year-error.php');
    //             exit();
    //         }
    //         return $currentYear;
    //     }
    // }



    // get year and month for salary
    // public function updateOrInsertSalary($employeeId, $amount)
    // {
    //     $currentDate = $this->getYearMonth();
    //     $year = $currentDate['year'];
    //     $month = $currentDate['month'];

    //     $salaryRecord = $this->db->select(
    //         "SELECT id, total_salary FROM employee_salaries WHERE employee_id = ? AND year = ? AND month = ?",
    //         [$employeeId, $year, $month]
    //     )->fetch();

    //     if ($salaryRecord) {
    //         $newTotal = intval($salaryRecord['total_salary']) + intval($amount);
    //         $this->db->update("employee_salaries", $salaryRecord['id'], ['total_salary'], [$newTotal]);
    //     } else {
    //         $employee_infos = [
    //             'employee_id' => $employeeId,
    //             'year' => $year,
    //             'month' => $month,
    //             'total_salary' => intval($amount),
    //         ];
    //         $this->db->insert("employee_salaries", array_keys($employee_infos), $employee_infos);
    //     }
    // }

    // change month name
    // function convertMonthName($calendarType, $monthNumber)
    // {
    //     if ($calendarType == 'gregorian') {
    //         $gregorianMonths = [
    //             1 => 'January',
    //             2 => 'February',
    //             3 => 'March',
    //             4 => 'April',
    //             5 => 'May',
    //             6 => 'June',
    //             7 => 'July',
    //             8 => 'August',
    //             9 => 'September',
    //             10 => 'October',
    //             11 => 'November',
    //             12 => 'December'
    //         ];
    //         return $gregorianMonths[$monthNumber] ?? 'ماه نامعتبر!';
    //     } elseif ($calendarType == 'jalali') {
    //         $zodiacMonths = [
    //             1 => 'حمل',
    //             2 => 'ثور',
    //             3 => 'جوزا',
    //             4 => 'سرطان',
    //             5 => 'اسد',
    //             6 => 'سنبله',
    //             7 => 'میزان',
    //             8 => 'عقرب',
    //             9 => 'قوس',
    //             10 => 'جدی',
    //             11 => 'دلو',
    //             12 => 'حوت'
    //         ];
    //         return $zodiacMonths[$monthNumber] ?? 'ماه نامعتبر!';
    //     } else {
    //         return 'نوع تقویم نامعتبر است!';
    //     }
    // }



    ///////////////////


    // private $shamsiToMiladi = [
    //     1 => 4,  // فروردین → آوریل
    //     2 => 5,  // اردیبهشت → می
    //     3 => 6,  // خرداد → ژوئن
    //     4 => 7,  // تیر → جولای
    //     5 => 8,  // مرداد → آگوست
    //     6 => 9,  // شهریور → سپتامبر
    //     7 => 10, // مهر → اکتبر
    //     8 => 11, // آبان → نوامبر
    //     9 => 12, // آذر → دسامبر
    //     10 => 1, // دی → ژانویه
    //     11 => 2, // بهمن → فوریه
    //     12 => 3  // اسفند → مارس
    // ];

    // private $monthNamesMiladi = [
    //     1 => 'ژانویه',
    //     2 => 'فوریه',
    //     3 => 'مارس',
    //     4 => 'آوریل',
    //     5 => 'می',
    //     6 => 'ژوئن',
    //     7 => 'جولای',
    //     8 => 'آگوست',
    //     9 => 'سپتامبر',
    //     10 => 'اکتبر',
    //     11 => 'نوامبر',
    //     12 => 'دسامبر'
    // ];

    // private $monthNamesShamsi = [
    //     1 => 'حمل',
    //     2 => 'ثور',
    //     3 => 'جوزا',
    //     4 => 'سرطان',
    //     5 => 'اسد',
    //     6 => 'سنبله',
    //     7 => 'میزان',
    //     8 => 'عقرب',
    //     9 => 'قوس',
    //     10 => 'جدی',
    //     11 => 'دلو',
    //     12 => 'حوت'
    // ];

    // public function convertMonth($calendarType, $month)
    // {
    //     if ($calendarType === 'shamsi') {
    //         return $this->monthNamesShamsi[$month];
    //     } else { // اگر میلادی بود
    //         $miladiMonth = $this->shamsiToMiladi[$month] ?? $month;
    //         return $this->monthNamesMiladi[$miladiMonth];
    //     }
    // }

    // used
    // $month_name = $calendar->convertMonth($calendar_type, $month_number);
}
