<?php

namespace App;

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

trait SendMail 
{
    // sender mail
    public function sendMail($emailAddress, $subject, $body)
    {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
            $mail->CharSet = "UTF-8"; //Enable verbose debug output
            $mail->isSMTP(); //Send using SMTP
            $mail->Host = MAIL_HOST; //Set the SMTP server to send through
            $mail->SMTPAuth = SMTP_AUTH; //Enable SMTP authentication
            $mail->Username = MAIL_USERNAME; //SMTP username
            $mail->Password = MAIL_PASSWORD; //SMTP password
            $mail->SMTPSecure = 'tls'; //Enable implicit TLS encryption
            $mail->Port = MAIL_PORT; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            //Recipients
            $mail->setFrom(SENDER_MAIL, SENDER_NAME);
            $mail->addAddress($emailAddress);
            //Content
            $mail->isHTML(true); //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
    }

    // send forgot message
    public function forgotMessage($username, $forgotToken)
    {
        $message = '
        <div style="text-align: center;">
            <h1 style="color: #218c00;">' . _forgot_pass . '</h1> 
            <p style="font-size: 16px;">' . $username . _reset_pass_link . '</p>
            <div><a href="' . url('reset-password-form/' . $forgotToken) . '">' . _change_password . '</a></div>
            </div>
            ';
        return $message;
    }

    // send activation message
    public function activationMessage($username, $verifyToken)
    {
        $message = '
            <div style="text-align: center;">
                <h1 style="color: #218c00;">' . _account_active . '</h1>
                <p style="font-size: 16px;">' . $username . _click_btn_active . '</p>
                <di><a href="' . url('active/' . $verifyToken) . '">' . _active_account . '</a></di>
            </div>
            ';
        return $message;
    }

}
