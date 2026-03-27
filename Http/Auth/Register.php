<?php

namespace Auth;

require_once 'Http/Auth/Auth.php';
require_once 'Http/Controllers/SendMail.php';
require_once 'Http/Controllers/Middleware.php';

use App\Middleware;
use App\SendMail;
use Auth\Auth;

use database\DataBase;


class Register extends Auth
{
    use SendMail;
    use Middleware;
    // insert and check user for register
    public function registerStore($request)
    {
        if (!empty($request['g-recaptcha-response'])) {
            $secret = _secret;
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $request['g-recaptcha-response']);
            $responseData = json_decode($verifyResponse);
            if ($responseData->success) {
                unset($request['g-recaptcha-response']);
                //////////////////////////////////
                if (empty($request['email']) || empty($request['name']) || empty($request['password'])) {
                    if (empty($request['email']) && empty($request['name']) && empty($request['password'])) {
                        flash('email_war', 'war');
                        flash('name_war', 'war');
                        flash('pass_war', 'war');
                    }
                    if (empty($request['email'])) {
                        flash('email_war', 'war');
                    }
                    if (empty($request['name'])) {
                        flash('name_war', 'war');
                    }
                    if (empty($request['password'])) {
                        flash('pass_war', 'war');
                    }
                    flash('warning', _empty_info);
                    flash('email', $request['email']);
                    flash('name', $request['name']);
                    flash('password', $request['password']);
                    $this->redirectBack();
                    exit();
                } else if (strlen($request['password']) < 8) {
                    flash('warning', _error_pass);
                    flash('email', $request['email']);
                    flash('name', $request['name']);
                    flash('password', $request['password']);
                    flash('pass_war', 'war');
                    $this->redirectBack();
                    exit();
                } else if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
                    flash('warning', _error_email);
                    flash('email', $request['email']);
                    flash('name', $request['name']);
                    flash('password', $request['password']);
                    flash('email_war', 'war');
                    $this->redirectBack();
                    exit();
                } else {
                    $db = new DataBase();
                    $user = $db->select('SELECT * FROM al_users WHERE email = ?', [$request['email']])->fetch();
                    if ($user != null) {
                        flash('warning', _already_email);
                        flash('email', $request['email']);
                        flash('name', $request['name']);
                        flash('password', $request['password']);
                        flash('email_war', 'war');
                        $this->redirectBack();
                        exit();
                    } else {
                        $randomToken = $this->random();
                        $activationMessage = $this->activationMessage($request['name'], $randomToken);
                        $result = $this->sendMail($request['email'], _account_active, $activationMessage);
                        if ($result) {
                            $user_input = array('<', '>', '/', '"', '\'', '(', ')', 'query', ',', ';', '[', ']', '$', 'SELEC', ':', '-', '=', '.', '#', '*');
                            $request['name'] = str_replace($user_input, "", $request['name']);
                            $request['name'] = $this->input_data($request['name']);
                            $request['verify_token'] = $randomToken;
                            $request['password'] = $this->hash($request['password']);
                            $db->insert('al_users', array_keys($request), $request);
                            flash('success', _sended_mail);
                            $this->redirect('login');
                            exit();
                        } else {
                            flash('warning', _problem_send_email);
                            flash('email', $request['email']);
                            flash('name', $request['name']);
                            flash('password', $request['password']);
                            $this->redirectBack();
                            exit();
                        }
                    }
                }
            }
        } else {
            flash('warning', _not_robot);
            flash('email', $request['email']);
            flash('name', $request['name']);
            flash('password', $request['password']);
            flash('not_r', 'war');
            $this->redirectBack();
            exit();
        }
    } // end check user for register
}
