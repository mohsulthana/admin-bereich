<?php
namespace Gemueseeggli\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use Gemueseeggli\Database\Model\User;
use Gemueseeggli\Database\Model\Region;
use Gemueseeggli\Database\Model\Abo;
use Gemueseeggli\Database\Model\Article;
use Gemueseeggli\Database\Model\Origin;
use \Datetime;

/**
 * Class HomepageController
 * @package Gemueseeggli\Controller
 */
class PagesController extends AbstractController
{
    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function homepage(Request $request, Response $response, $args)
    {
        $articles = $this->db->getArticles();
        $data = ['articles' => $articles];
        return $this->view->render($response, 'website/pages/homepage.twig', $data);
    }

    public function contact(Request $request, Response $response, $args)
    {
        $data = [];
        $firstNumber = rand(1,10);
        $secondNumber = rand(1,10);
        $controlNumber = $firstNumber + $secondNumber;
        $data['firstNumber'] = $firstNumber;
        $data['secondNumber'] = $secondNumber;
        $data['controlNumber'] = $controlNumber;

        return $this->view->render($response, 'website/pages/contact.twig', $data);
    }

    public function pause(Request $request, Response $response, $args)
    {
        $currentUser = $this->db->getUser($this->user->id);
        $unsortedPauses = $currentUser->pauses; //->first();
        $iterator = $unsortedPauses->getIterator();
        $iterator->uasort(function ($a, $b) 
        {
            return ($a->enddate > $b->enddate) ? -1 : 1;
        });
        $sortedPauses = new \Doctrine\Common\Collections\ArrayCollection(iterator_to_array($iterator));
        $pause = $sortedPauses->first();
        if($pause != false)
        {
            $pauseEndDate = $pause->enddate;
            $todayDate = new DateTime();
            $todayDate = new DateTime($todayDate->format('Y-m-d'));
            if($pauseEndDate < $todayDate)
            {
                $pause = false;
            }
        }

        $data = ['user' => $currentUser, 'pause' => $pause];

        return $this->view->render($response, 'website/pages/pause.twig', $data);
    }

    public function noaccess(Request $request, Response $response, $args)
    {
        $data = [];
        return $this->view->render($response, 'website/pages/noaccess.twig', $data);
    }

    public function myabos(Request $request, Response $response, $args)
    {
        $data = ['userid' => $this->user->id];
        return $this->view->render($response, 'website/pages/myabos.twig', $data);
    }

    public function myabodetail(Request $request, Response $response, $args)
    {
        $abo = $this->db->getAbo($args['id']);
        $data = ['abo' => $abo];
        return $this->view->render($response, 'website/pages/myabodetail.twig', $data);
    }

    public function myprofile(Request $request, Response $response, $args)
    {
        $currentUser = $this->db->getUser($this->user->getId());
        $shippingAddress = $currentUser->getShippingAddress();
        $billingAddress = $currentUser->getBillingAddress();
        //$shippingAddress = $this->db->getAddress(1);
        //$billingAddress = $this->db->getAddress(2);
        $data = ['shippingAddress' => $shippingAddress, 'billingAddress' => $billingAddress];
        return $this->view->render($response, 'website/pages/myprofile.twig', $data);
    }

    public function abodetails(Request $request, Response $response, $args)
    {
        $article = $this->db->getArticle($args['id']);
        $origins = $this->db->getOrigins();
        $data = ['article' => $article, 'origins' => $origins];
        return $this->view->render($response, 'website/pages/abodetails.twig', $data);
    }

    public function checkout(Request $request, Response $response, $args)
    {
        $currentUser = null;
        $hasUser = false;
        $shippingAddress = null;
        $billingAddress = null;
        if($this->user != null)
        {
            $hasUser = true;
            $currentUser = $this->db->getUser($this->user->getId());
            $shippingAddress = $currentUser->shippingAddress;
            $billingAddress = $currentUser->billingAddress;
        }
        $data = ['shippingAddress' => $shippingAddress, 'billingAddress' => $billingAddress, 'hasUser' => $hasUser];
        return $this->view->render($response, 'website/pages/checkout.twig', $data);
    }
}
