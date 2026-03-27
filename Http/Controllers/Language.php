<?php

namespace App;

require_once 'Http/Controllers/App.php';

use database\DataBase;

class Language extends App
{

    public function dari()
    {
        setcookie("lang", 'fa', time() + (86400 * 30 * 6));
        $this->redirectBack();
    }

    public function pashto()
    {
        setcookie("lang", 'pashto', time() + (86400 * 30 * 6));
        $this->redirectBack();
    }

    public function en()
    {
        setcookie("lang", 'en', time() + (86400 * 30 * 6));
        $this->redirectBack();
    }
}
