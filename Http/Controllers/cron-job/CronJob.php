<?php

namespace App;

require_once 'Http/Controllers/App.php';

class CronJob extends App
{
    public function cronJob()
    {
        $result = $this->db->select('SELECT * FROM classes WHERE `status` NOT IN (2, 3, 6)')->fetchAll();

        foreach ($result as $class) {
            $currentTimestamp = time();

            if ($class['status'] == 4 && $class['start_class_at'] <= $currentTimestamp) {
                $this->db->update('classes', $class['id'], ['status'], [1]);
            }

            if ($class['status'] == 1 && $class['end_class_at'] <= $currentTimestamp) {
                $this->db->update('classes', $class['id'], ['status'], [2]);
            }

            if ($class['status'] == 5) {
                $closureData = $this->db->select('SELECT * FROM class_closure WHERE `class_id` = ?', [$class['id']])->fetch();

                if ($closureData) {
                    $closureStart = intval($closureData['date_it_closure']);
                    $closureDays = intval($closureData['number_of_closure']);
                    $closureEnd = $closureStart + ($closureDays * 86400);

                    if ($currentTimestamp >= $closureEnd) {
                        $this->db->update('classes', $class['id'], ['status'], [1]);
                    }
                }
            }
        }
    }
}
