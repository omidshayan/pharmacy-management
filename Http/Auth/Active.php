<?php
namespace Auth;

use Auth\Register;
use database\Database;

class Active extends Register{

    public function active($verifyToken){
        $db = new Database();
        $user = $db->select("SELECT * FROM al_users WHERE verify_token = ? AND `state` = 0;", [$verifyToken])->fetch();
        if($user == null){
            $this->redirect('login');
        }
        else{
            $result = $db->update('al_users', $user['id'], ['state'], [1]);
            $this->redirect('login');
        }
    }
}