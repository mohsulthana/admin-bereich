<?php

namespace Gemueseeggli\src\helpers;

use PHPMailer;

class MailHelper
{
    public function passwordResetMail($user)
    {
        $filePath = $_SERVER['DOCUMENT_ROOT'].'/../templates/mails/passwordReset.html';
        $template = file_get_contents($filePath, FILE_USE_INCLUDE_PATH);

        $template = str_replace('$$passwordResetLink$$', $_SERVER['HTTP_ORIGIN'].'/forgotpassword/'.$user->passwordresetcode, $template);

        $subject = "Passwort zurücksetzen - Gemüse Eggli";

        $body = $template;
        
        $this->sendMail(array($user->getUsername()), $subject, $body);
    }

    public function contactMail($name, $mail, $message)
    {
        $filePath = $_SERVER['DOCUMENT_ROOT'].'/../templates/mails/contact.html';
        $template = file_get_contents($filePath, FILE_USE_INCLUDE_PATH);

        $template = str_replace('$$name$$', $name, $template);
        $template = str_replace('$$mail$$', $mail, $template);
        $template = str_replace('$$message$$', nl2br($message), $template);

        $subject = "Neue Kontaktanfrage";

        $body = $template;

        $this->sendMail(array(getenv('MAIL_CONTACT')), $subject, $body);
    }

    public function registrationMail($user)
    {
        $filePathAdmin = $_SERVER['DOCUMENT_ROOT'].'/../templates/mails/registration.admin.html';
        $templateAdmin = file_get_contents($filePathAdmin, FILE_USE_INCLUDE_PATH);

        $templateAdmin = str_replace('$$mail$$', $user->username, $templateAdmin);

        $templateAdmin = str_replace('$$customerlink$$', $_SERVER['HTTP_ORIGIN'].'/admin/users?userId='.$user->id, $templateAdmin);

        $subjectAdmin = "Neuer Kunde - Gemüse Eggli";

        $bodyAdmin = $templateAdmin;
        
        $this->sendMail(array(getenv('MAIL_CONTACT')), $subjectAdmin, $bodyAdmin);

        $filePathUser = $_SERVER['DOCUMENT_ROOT'].'/../templates/mails/registration.user.html';
        $templateUser = file_get_contents($filePathUser, FILE_USE_INCLUDE_PATH);

        $templateUser = str_replace('$$username$$', $user->username, $templateUser);

        $templateUser = str_replace('$$website$$', $_SERVER['HTTP_ORIGIN'], $templateUser);

        $subjectUser = "Registration - Gemüse Eggli";

        $bodyUser = $templateUser;
        
        $this->sendMail(array($user->username), $subjectUser, $bodyUser);
    }

    public function checkoutAboMail($user, $abo)
    {
        $filePathAdmin = $_SERVER['DOCUMENT_ROOT'].'/../templates/mails/aboCheckout.admin.html';
        $templateAdmin = file_get_contents($filePathAdmin, FILE_USE_INCLUDE_PATH);

        $templateAdmin = str_replace('$$mail$$', $user->username, $templateAdmin);
        $templateAdmin = str_replace('$$aboprice$$', $abo->articletype->price, $templateAdmin);
        $templateAdmin = str_replace('$$aboarticle$$', $abo->articletype->article->name, $templateAdmin);
        $templateAdmin = str_replace('$$customerlink$$', $_SERVER['HTTP_ORIGIN'].'/admin/users?userId='.$user->id, $templateAdmin);

        $subjectAdmin = "Neuer Aboabschluss - Gemüse Eggli";

        $bodyAdmin = $templateAdmin;
        
        $this->sendMail(array(getenv('MAIL_CONTACT')), $subjectAdmin, $bodyAdmin);
    }

    public function deleteAboMail($user, $abo, $aboQuitReason)
    {
        $filePathAdmin = $_SERVER['DOCUMENT_ROOT'].'/../templates/mails/aboDelete.admin.html';
        $templateAdmin = file_get_contents($filePathAdmin, FILE_USE_INCLUDE_PATH);

        $templateAdmin = str_replace('$$mail$$', $user->username, $templateAdmin);
        $templateAdmin = str_replace('$$aboprice$$', $abo->articletype->price, $templateAdmin);
        $templateAdmin = str_replace('$$aboarticle$$', $abo->articletype->article->name, $templateAdmin);
        $templateAdmin = str_replace('$$aboto$$', $abo->enddate->format('d.m.Y'), $templateAdmin);
        $templateAdmin = str_replace('$$quitreason$$', $aboQuitReason, $templateAdmin);
        $templateAdmin = str_replace('$$customerlink$$', $_SERVER['HTTP_ORIGIN'].'/admin/users?userId='.$user->id, $templateAdmin);

        $subjectAdmin = "Abokündigung - Gemüse Eggli";

        $bodyAdmin = $templateAdmin;
        
        $this->sendMail(array(getenv('MAIL_CONTACT')), $subjectAdmin, $bodyAdmin);
    }

    public function aboUpdateMail($user, $abo)
    {
        $filePathAdmin = $_SERVER['DOCUMENT_ROOT'].'/../templates/mails/aboChange.admin.html';
        $templateAdmin = file_get_contents($filePathAdmin, FILE_USE_INCLUDE_PATH);

        $templateAdmin = str_replace('$$mail$$', $user->username, $templateAdmin);
        $templateAdmin = str_replace('$$aboarticle$$', $abo->articletype->article->name, $templateAdmin);
        $templateAdmin = str_replace('$$customerlink$$', $_SERVER['HTTP_ORIGIN'].'/admin/users?userId='.$user->id, $templateAdmin);

        $subjectAdmin = "Aboänderung - Gemüse Eggli";

        $bodyAdmin = $templateAdmin;
        
        $this->sendMail(array(getenv('MAIL_CONTACT')), $subjectAdmin, $bodyAdmin);
    }

    public function breakDeleteMail($user)
    {
        $filePathAdmin = $_SERVER['DOCUMENT_ROOT'].'/../templates/mails/breakDelete.admin.html';
        $templateAdmin = file_get_contents($filePathAdmin, FILE_USE_INCLUDE_PATH);

        $templateAdmin = str_replace('$$mail$$', $user->username, $templateAdmin);
        $templateAdmin = str_replace('$$break$$', $user->getPauseFormated(), $templateAdmin);
        $server = str_replace('/pause', '', $_SERVER['HTTP_REFERER']);
        $templateAdmin = str_replace('$$customerlink$$', $server.'/admin/users?userId='.$user->id, $templateAdmin);

        $subjectAdmin = "Unterbrechung gelöscht - Gemüse Eggli";

        $bodyAdmin = $templateAdmin;
        
        $this->sendMail(array(getenv('MAIL_CONTACT')), $subjectAdmin, $bodyAdmin);
    }

    public function breakCreateMail($user)
    {
        $filePathAdmin = $_SERVER['DOCUMENT_ROOT'].'/../templates/mails/breakCreate.admin.html';
        $templateAdmin = file_get_contents($filePathAdmin, FILE_USE_INCLUDE_PATH);

        $templateAdmin = str_replace('$$mail$$', $user->username, $templateAdmin);
        $templateAdmin = str_replace('$$break$$', $user->getPauseFormated(), $templateAdmin);
        $server = str_replace('/pause', '', $_SERVER['HTTP_REFERER']);
        $templateAdmin = str_replace('$$customerlink$$', $server.'/admin/users?userId='.$user->id, $templateAdmin);

        $subjectAdmin = "Unterbrechung erstellt - Gemüse Eggli";

        $bodyAdmin = $templateAdmin;
        
        $this->sendMail(array(getenv('MAIL_CONTACT')), $subjectAdmin, $bodyAdmin);
    }

    public function addressChangeMail($user, $changes)
    {
        $filePathAdmin = $_SERVER['DOCUMENT_ROOT'].'/../templates/mails/addresseChange.admin.html';
        $templateAdmin = file_get_contents($filePathAdmin, FILE_USE_INCLUDE_PATH);

        $templateAdmin = str_replace('$$mail$$', $user->username, $templateAdmin);
        $templateAdmin = str_replace('$$changes$$', $changes, $templateAdmin);
        $templateAdmin = str_replace('$$customerlink$$', $_SERVER['HTTP_ORIGIN'].'/admin/users?userId='.$user->id, $templateAdmin);

        $subjectAdmin = "Adressänderung - Gemüse Eggli";

        $bodyAdmin = $templateAdmin;
        
        $this->sendMail(array(getenv('MAIL_CONTACT')), $subjectAdmin, $bodyAdmin);
    }    

    private function configureMail()
    {
        $mail = new PHPMailer;

        $mail->isSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->Host = getenv('MAIL_SMTP_HOST');
        $mail->SMTPAuth = true;
        $mail->Username = getenv('MAIL_SMTP_USERNAME');
        $mail->Password = getenv('MAIL_SMTP_PASSWORD');
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->isHTML(true);

        //$mail->SMTPDebug = 3;

        $mail->setFrom(getenv('MAIL_FROM'), getenv('MAIL_FROM_NAME'));

        return $mail;
    }

    public function sendMail($to, $subject, $body)
    {
        $mail = $this->configureMail();

        foreach ($to as $receiver) {
            if (filter_var($receiver, FILTER_VALIDATE_EMAIL)) {
                $mail->addAddress($receiver);
            }          
        }

        $mail->Subject = $subject;
        $mail->Body = $body;
    
        $mail->send();
    }
}
