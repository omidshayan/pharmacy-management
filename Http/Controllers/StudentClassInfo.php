<?php

namespace App;

require_once 'Http/Controllers/App.php';

use database\DataBase;

class StudentClassInfo extends App
{

    public function getClassInfo($class_id)
    {
        return $this->db->select('SELECT * FROM classes WHERE id = ?', [$class_id])->fetch();
    }

    // get student informations
    public function getStudentInfo($student_id)
    {
        return $this->db->select(
            '
        SELECT s.id, s.name, s.created_at, p.father_name 
        FROM students_sis s
        LEFT JOIN parents p ON s.id = p.student_id
        WHERE s.id = ?',
            [$student_id]
        )->fetch();
    }

    // get all student informations
    public function getAllStudentInfo($student_id)
    {
        $query = "SELECT 
            s.id AS student_id, 
            s.created_at AS student_created_at, 
            s.*, 
            o.*, 
            p.*, 
            sp.*, 
            cs.* 
          FROM students_sis s 
          LEFT JOIN other_stu_infos o ON s.id = o.student_id 
          LEFT JOIN parents p ON s.id = p.student_id 
          LEFT JOIN sponsors sp ON s.id = sp.student_id 
          LEFT JOIN certificate_stu cs ON s.id = cs.student_id 
          WHERE s.id = :id";
        $params = ['id' => $student_id];
        return $this->db->select($query, $params)->fetch();
    }

    // get student all payment and discount
    public function getStudentClassesWithDetails($student_id)
    {
        $query = 'SELECT classes_id, MIN(created_at) AS created_at 
              FROM students_class_infos 
              WHERE student_id = ? 
              GROUP BY classes_id ORDER BY id DESC';
        $student_classes = $this->db->select($query, [$student_id])->fetchAll();

        $classes = [];
        foreach ($student_classes as $student_class) {
            $class = $this->db->select('SELECT * FROM classes WHERE id = ?', [$student_class['classes_id']])->fetch();
            if ($class) {
                $classInfo = array_merge($class, ['created_at' => $student_class['created_at']]);

                // محاسبه مجموع پرداختی و تخفیف
                $paymentSummary = $this->db->select(
                    'SELECT SUM(payment) AS total_payment, SUM(discount) AS total_discount 
                 FROM payment_details 
                 WHERE student_id = ? AND class_id = ?',
                    [$student_id, $student_class['classes_id']]
                )->fetch();

                $totalPayment = $paymentSummary['total_payment'] ?? 0;
                $totalDiscount = $paymentSummary['total_discount'] ?? 0;
                $tuitionFee = $class['cost']; // هزینه کلاس
                $remaining = $tuitionFee - ($totalPayment + $totalDiscount);

                $classInfo['total_payment'] = $totalPayment;
                $classInfo['total_discount'] = $totalDiscount;
                $classInfo['remaining'] = $remaining;

                $classes[] = $classInfo;
            }
        }

        return $classes;
    }

    // get terms informations
    public function getTerms($class_id)
    {
        return $this->db->select('SELECT * FROM terms WHERE classes_id = ?', [$class_id])->fetchAll();
    }

    // get date in term register
    public function getTermRegistrationDates($student_id, $class_id)
    {
        $term_registration_dates = $this->db->select(
            '
        SELECT term_id, created_at AS registration_date 
        FROM students_class_infos 
        WHERE classes_id = ? AND student_id = ?
        ',
            [$class_id, $student_id]
        )->fetchAll();

        $registration_dates = [];
        foreach ($term_registration_dates as $reg) {
            $registration_dates[$reg['term_id']] = $reg['registration_date'];
        }

        return $registration_dates;
    }

    // get payment details term
    public function getPaymentDetailsByTerm($student_id, $class_id)
    {
        // دریافت اطلاعات پرداخت‌ها به همراه تخفیف‌ها
        $payment_data = $this->db->select('
                SELECT term_id, SUM(payment) AS term_paid, SUM(discount) AS term_discount, 
                    payment, discount, created_at, reason_discount 
                FROM payment_details 
                WHERE class_id = ? AND student_id = ? 
                GROUP BY term_id, created_at 
                ORDER BY term_id, created_at ASC
            ', [$class_id, $student_id])->fetchAll();

        // پردازش مجموع پرداخت‌ها و تخفیف‌ها
        $total_payment = ['total_paid' => 0, 'total_discount' => 0];
        $term_details = [];

        // پردازش هر رکورد پرداخت
        foreach ($payment_data as $pay) {
            // اضافه کردن مجموع پرداخت‌ها و تخفیف‌ها
            $total_payment['total_paid'] += $pay['term_paid'];
            $total_payment['total_discount'] += $pay['term_discount'];

            $term_id = $pay['term_id'];

            // اگر ترم هنوز در آرایه نیست، آن را اضافه می‌کنیم
            if (!isset($term_details[$term_id])) {
                $term_details[$term_id] = [];
            }

            // اضافه کردن جزئیات هر پرداخت به ترم خاص
            $term_details[$term_id][] = [
                'payment' => $pay['payment'],
                'discount' => $pay['discount'],
                'created_at' => $pay['created_at'],
                'reason_discount' => $pay['reason_discount']
            ];
        }

        // بازگشت مجموع پرداخت‌ها و جزئیات ترم‌ها
        return [
            'total_payment' => $total_payment,
            'term_details' => $term_details
        ];
    }

    public function getStudentAttendance($student_id, $class_id)
    {
        $attendance_details = $this->db->select(
            'SELECT * FROM students_attendance_details WHERE student_id = ? AND classes_id = ?',
            [$student_id, $class_id]
        )->fetchAll();

        $grouped_attendance = [];

        foreach ($attendance_details as $record) {
            $term_id = $record['term_id'];
            $time_name = $record['time_name'];

            if (!isset($grouped_attendance[$term_id])) {
                $grouped_attendance[$term_id] = [];
            }

            if (!isset($grouped_attendance[$term_id][$time_name])) {
                $grouped_attendance[$term_id][$time_name] = [
                    'total_absent' => 0,
                    'total_vocation' => 0,
                    'records' => [],
                ];
            }

            if (!empty($record['absent'])) {
                $grouped_attendance[$term_id][$time_name]['total_absent']++;
            }
            if (!empty($record['vocation'])) {
                $grouped_attendance[$term_id][$time_name]['total_vocation']++;
            }

            $grouped_attendance[$term_id][$time_name]['records'][] = $record;
        }

        return $grouped_attendance;
    }

    // get student name and father name
    public function getStudentNameAndFatherName($student_id)
    {
        return $this->db->select(
            '
        SELECT s.id, s.name, s.created_at, p.father_name 
        FROM students_sis s
        LEFT JOIN parents p ON s.id = p.student_id
        WHERE s.id = ?',
            [$student_id]
        )->fetch();
    }








    ///////////////////

    // exist term scorse?
    public function existTermScores($term_id)
    {
        $get_scores = $this->db->select('SELECT * FROM scores WHERE term_id = ?', [$term_id])->fetch();
        return $get_scores;
    }

    // get scores
    public function getTermScores($term_id)
    {
        $get_scores = $this->db->select('SELECT * FROM scores WHERE term_id = ?', [$term_id])->fetchAll();
        if (!$get_scores) {
            return false;
        }

        $scores = [];
        foreach ($get_scores as $score) {
            $scores[$score['student_id']] = $score;
        }

        return $scores;
    }

    // get scores student term
    public function getStudentScoreTerm($student_id, $term_id)
    {
        $student_score = $this->db->select('SELECT *, (paper_score + speaking_score + listening_score + workbook_score + class_activity_score + attendance_score) AS total_score FROM scores WHERE student_id = ? AND term_id = ?', [$student_id, $term_id])->fetch();
        return $student_score;
    }

    // get scores studnet terms
    public function getStudentScoresTerms($student_id, $class_id)
    {
        $terms = $this->db->select('SELECT DISTINCT term_id FROM scores WHERE class_id = ? ORDER BY id ASC', [$class_id])->fetchAll();

        $scores = [];

        foreach ($terms as $term) {
            $term_id = $term['term_id'];

            $student_score = $this->db->select('SELECT *, (paper_score + speaking_score + listening_score + workbook_score + class_activity_score + attendance_score) AS total_score FROM scores WHERE student_id = ? AND term_id = ? AND class_id = ?', [$student_id, $term_id, $class_id])->fetch();

            if ($student_score) {
                $student_score['grade'] = $this->calculateGrade($student_score['total_score']);

                $scores[] = [
                    'term_id' => $term_id,
                    'total_score' => $student_score['total_score'],
                    'grade' => $student_score['grade'],
                    'details' => $student_score,
                ];
            }
        }

        return $scores;
    }



    // get grades
    public function calculateGrade($total_score)
    {
        if ($total_score >= 90) {
            return 'A';
        } elseif ($total_score >= 80) {
            return 'B';
        } elseif ($total_score >= 70) {
            return 'C';
        } elseif ($total_score >= 60) {
            return 'D';
        } else {
            return 'F';
        }
    }
}
