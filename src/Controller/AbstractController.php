<?php
namespace Gemueseeggli\Controller;

use Slim\Container;
use Slim\Views\Twig as TwigViews;
use Gemueseeggli\Controller\Base;
use Gemueseeggli\Database\DBConnector;
use Gemueseeggli\src\helpers\MailHelper;

/**
 * Class AbstractController
 * @package Gemueseeggli\Controller
 */
abstract class AbstractController
{
    /** @var TwigViews view */
    protected $view;

    protected $db;

    protected $user;

    protected $log;

    protected $mail;

    /**
     * AbstractController constructor.
     * @param Container $c
     */
    public function __construct(Container $c)
    {
        $this->view = $c->get('view');
        $this->db = new DBConnector($c);
        $this->log = $c->get('logger');
        $this->loadGlobalVariables();
        $this->mail = new MailHelper();
    }

    private function loadGlobalVariables()
    {
        if (isset($_SESSION['user'])) {
            $user = unserialize($_SESSION['user']);
            $this->view['user'] = $user;
            $this->user = $user;
        }
    }

    protected function isEmail($email)
    {
        return (preg_match("/(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/", $email) || !preg_match("/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/", $email)) ? false : true;
    }
}
