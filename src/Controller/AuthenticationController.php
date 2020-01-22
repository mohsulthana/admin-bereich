<?php
namespace Gemueseeggli\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use \Datetime;

/**
 * Class HomepageController
 * @package Gemueseeggli\Controller
 */
class AuthenticationController extends AbstractController
{
    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function login(Request $request, Response $response, $args)
    {
        $loginForm = $request->getParsedBody();
        $email = $loginForm['loginEmail'];
        $password = $loginForm['loginPassword'];
        $foundUser = $this->db->authenticateUser($email, $password);
        $responseArray = [];
        if ($foundUser != null) {
            $_SESSION['user'] = serialize($foundUser);
            $responseArray['user'] = $foundUser->withoutPassword();
        }
        return $response->withJSON($responseArray);
    }

    public function registration(Request $request, Response $response, $args)
    {
        $registrationForm = $request->getParsedBody();
        $email = $registrationForm['emailRegister'];
        $password = $registrationForm['passwordRegister'];
        $passwordConfirm = $registrationForm['passwordConfirmRegister'];
        $responseArray = [];
        if (empty($email)) {
            $responseArray['Email']  = 'Es wurde keine Email-Addresse mitgegeben!';
            $response = $response->withStatus(500);
        }
        else if ($this->db->getUserByUsername($email) != null) {
            $responseArray['Email']  = 'Es ist bereits ein Konto mit dieser E-Mail registriert!';
            $response = $response->withStatus(500);
        }
        else if (empty($password)) {
            $responseArray['Passwort']  = 'Das Passwortfeld darf nicht leer sein!';
            $response = $response->withStatus(500);
        }
        else if ($password != $passwordConfirm) {
            $responseArray['Passwort']  = 'Die Passwörter stimmen nicht überein!';
            $response = $response->withStatus(500);
        } else {
            $responseArray['answer']  = 'Ihr Benutzer wurde erfolgreich erstellt.';
            $newUser = $this->db->addUser($email, $password);
            $this->mail->registrationMail($newUser);
            $_SESSION['user'] = serialize($newUser);
            $responseArray['user']  = $newUser->withoutPassword();
        }
        return $response->withJSON($responseArray);
    }

    public function register(Request $request, Response $response, $args)
    {
        if ($this->user != null) {
            return $response->withStatus(302)->withHeader('Location', '/');
        }
        $data = [];
        return $this->view->render($response, 'website/pages/register.twig', $data);
    }

    public function forgotpassword(Request $request, Response $response, $args)
    {
        if ($this->user != null) {
            return $response->withStatus(302)->withHeader('Location', '/');
        }
        $data = [];
        if (array_key_exists('hash', $args)) {
            $data['hash'] = $args['hash'];
        }
        return $this->view->render($response, 'website/pages/forgotpassword.twig', $data);
    }

    public function resetpassword(Request $request, Response $response, $args)
    {
        $loginForm = $request->getParsedBody();
        $email = $loginForm['resetEmail'];
        $foundUser = $this->db->getUserByUsername($email);
        $foundUser->setPasswordresetdate(new DateTime());
        $foundUser->setPasswordresetcode(md5($foundUser->getPasswordresetdate()->format('Y-m-d H:i:s')));
        $this->db->save();
        $this->mail->passwordResetMail($foundUser);
        $responseArray = [];
        return $response->withJSON($responseArray);
    }

    public function setpassword(Request $request, Response $response, $args)
    {
        $setForm = $request->getParsedBody();
        $hash = $setForm['hash'];
        $password = $setForm['setPassword'];
        $passwordConfirm = $setForm['setPasswordConfirm'];
        $responseArray = [];
        if ($password == $passwordConfirm) {
            $user = $this->db->getUserByHash($hash);
            if ($user == null) {
                $responseArray['Schlüssel']  = 'Der Schlüssel zum Zurücksetzen ist nicht korrekt!';
                $response = $response->withStatus(500);
            } else {
                if ($user->getPasswordresetdate()->diff(new DateTime())->days > 1) {
                    $responseArray['Schlüssel']  = 'Der Schlüssel ist mehr als 24 Stunden alt und daher nicht mehr gültig!';
                    $response = $response->withStatus(500);
                } else {
                    $user->setPasswordresetcode(null);
                    $user->setPasswordresetdate(null);
                    $user->setPassword(md5($password));
                    $this->db->save();
                    $_SESSION['user'] = serialize($user);
                    $responseArray['user']  = $user->withoutPassword();
                }
            }
        } else {
            $responseArray['Passwort']  = 'Die Passwörter stimmen nicht überein!';
            $response = $response->withStatus(500);
        }
        return $response->withJSON($responseArray);
    }

    public function logout(Request $request, Response $response, $args)
    {
        session_destroy();
        $data = [];
        return $response->withStatus(302)->withHeader('Location', '/');
    }
}
