<?php

namespace Auth;

require_once 'Http/Controllers/Middleware.php';
require_once 'Http/Controllers/SendMail.php';

use App\Middleware;
use App\SendMail;
use Auth\Auth;
use database\Database;

class ForgotPassword extends Auth
{
    use Middleware;
    use SendMail;

    // employees forgot password rquest
    public function forgotRequest($request)
    {
        dd('ok');
        $this->middleware(true, true, 'students', true, $request);
        if (empty($request['email']) || !filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
            $this->flashMessage('error', 'لطفا ایمیل معتبر وارد نمائید!');
        } else {
            $user = $this->db->select('SELECT * FROM employees WHERE email = ? AND id = ?', [$request['email'], $_SESSION['sk_em_id']])->fetch();

            if ($user == null) {
                $this->flashMessage('error', 'کاربری با این ایمیل نشد!');
            } else {
                $randomToken = $this->random();
                $forgotMessage = $this->forgotMessage($user['employee_name'], $randomToken);
                $result = $this->sendMail($request['email'], _forgot_pass, $forgotMessage);
                if ($result) {
                    $this->db->update('employees', $user['id'], ['forgot_token', 'forgot_token_expire'], [$randomToken, date('Y-m-d H:i:s', strtotime('+10 minutes'))]);
                    $this->flashMessage('success', _forgot_link_email);
                } else {
                    $this->flashMessage('error', _error_email);
                }
            }
        }
    }

    public function resetPasswordView($forgot_token)
    {
        $this->middleware(true, true, 'students', true);
        require_once(BASE_PATH . '/resources/views/auth/reset-password.php');
    }

    public function resetPassword($request, $forgot_token)
    {
        $this->middleware(true, true, 'students', true, $request);

        if ($request['password'] !== $request['repeat_password']) {
            $this->flashMessage('error', 'رمزعبور با تکرار آن یکسان نیست!');
        }

        if (!isset($request['password']) || strlen($request['password']) < 8 || $request['password'] !== $request['repeat_password']) {
            $this->flashMessage('error', _error_pass);
        } else {
            $db = DataBase::getInstance();
            $user = $db->select('SELECT * FROM employees WHERE forgot_token = ?', [$forgot_token])->fetch();

            if ($user == null) {
                $this->flashMessage('error', _user_not_found);
            } else {
                if ($user['forgot_token_expire'] < date('Y-m-d H:i:s')) {
                    $this->flashMessage('error', _error_tocken);
                }
                if ($user) {
                    $db->update('employees', $user['id'], ['password'], [$this->hash($request['password'])]);
                    $this->redirect('profile');
                } else {
                    $this->flashMessage('error', _user_not_found);
                }
            }
        }
    }
}
