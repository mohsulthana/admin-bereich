<?php
namespace Gemueseeggli\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use Gemueseeggli\Database\Model\Abo;
use Gemueseeggli\Database\Model\Pause;
use \Datetime;
use Gemueseeggli\Database\Model\Address;
use Gemueseeggli\Database\Model\User;
use Gemueseeggli\Database\Model\Articletype;
use \FPDF;

/**
 * Class AdminController
 * @package Gemueseeggli\Controller
 */
class AdminController extends AbstractAdminController
{
    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function homepage(Request $request, Response $response, $args)
    {
        $data = [];
        return $this->view->render($response, 'admin/pages/homepage.twig', $data);
    }

    public function articles(Request $request, Response $response, $args)
    {
        $data = [];
        return $this->view->render($response, 'admin/pages/articles.twig', $data);
    }

    public function articledetails(Request $request, Response $response, $args)
    {
        $data = [];
        $article = $this->db->getArticle($args['id']);
        $data['article'] = $article;
        return $this->view->render($response, 'admin/pages/partials/articledetails.twig', $data);
    }

    public function articledetailsSave(Request $request, Response $response, $args)
    {
        $articleDetailsForm = $request->getParsedBody();
        $name = $articleDetailsForm['name'];
        $description = $articleDetailsForm['description'];
        $article = $this->db->getArticle($args['id']);
        $article->setName($name);
        $article->setDescription($description);
        $this->db->save();
        $responseArray = [];
        return $response->withJSON($responseArray);
    }

    public function abos(Request $request, Response $response, $args)
    {
        $data = [];
        return $this->view->render($response, 'admin/pages/abos.twig', $data);
    }

    public function users(Request $request, Response $response, $args)
    {
        $data = [];
        return $this->view->render($response, 'admin/pages/users.twig', $data);
    }

    public function userdetails(Request $request, Response $response, $args)
    {
        $data = [];
        $user = $this->db->getUser($args['id']);
        $regions = $this->db->getRegions();
        $salutations = $this->db->getSalutations();
        $data['user'] = $user;
        $data['regions'] = $regions;
        $data['salutations'] = $salutations;

        $unsortedPauses = $user->pauses;

        if($unsortedPauses != null){
                $iterator = $unsortedPauses->getIterator();
                $iterator->uasort(function ($a, $b) {
                    return ($a->enddate > $b->enddate) ? -1 : 1;
                });
                $sortedPauses = new \Doctrine\Common\Collections\ArrayCollection(iterator_to_array($iterator));
                $pause = $sortedPauses->first();
                if ($pause != false) {
                    $pauseEndDate = $pause->enddate;
                    $todayDate = new DateTime();
                    $todayDate = new DateTime($todayDate->format('Y-m-d'));
                    if ($pauseEndDate < $todayDate) {
                        $pause = false;
                    }
                }

                $data['pause'] = $pause;
        }

        return $this->view->render($response, 'admin/pages/partials/userdetails.twig', $data);
    }

    public function userdetailsSave(Request $request, Response $response, $args)
    {
        $userDetailsForm = $request->getParsedBody();

        $username = $userDetailsForm['username'];
        $passwordRegister = $userDetailsForm['passwordRegister'];
        $passwordConfirmRegister = $userDetailsForm['passwordConfirmRegister'];

        $responseArray = [];

        if (empty($username)) {
            $responseArray['Benutzername']  = 'Der Benutzername muss ausgefüllt sein!';
            $response = $response->withStatus(500);
        } else {
            if ($args['id'] == '0') {
                if ($passwordRegister != null || $passwordConfirmRegister != null) {
                    if ($passwordRegister == $passwordConfirmRegister) {
                        $user = $this->db->addUser($username, $passwordRegister);
                    } else {
                        $responseArray['Passwort']  = 'Die Passwörter stimmen nicht überein!';
                        $response = $response->withStatus(500);
                    }
                } else {
                    $user = $this->db->addUser($username, null);
                }
            } else {
                $user = $this->db->getUser($args['id']);

                if ($passwordRegister != null || $passwordConfirmRegister != null) {
                    if ($passwordRegister == $passwordConfirmRegister) {
                        $user->setPassword(md5($passwordRegister));
                    } else {
                        $responseArray['Passwort']  = 'Die Passwörter stimmen nicht überein!';
                        $response = $response->withStatus(500);
                    }
                }
            }

            if ($user != null) {
                $regionId = $userDetailsForm['region'];
                if (is_numeric($regionId)) {
                    $region = $this->db->getRegion(intval($regionId));
                    $user->setRegion($region);
                } else {
                    $user->setRegion(null);
                }

                if (array_key_exists('isActive', $userDetailsForm)) {
                    $user->isActive = 1;
                } else {
                    $user->isActive = 0;
                }
            }

            $user->username = $username;

            if (array_key_exists('breakFrom', $userDetailsForm) && array_key_exists('breakTo', $userDetailsForm)) {
                $breakFrom = $userDetailsForm['breakFrom'];
                $breakTo = $userDetailsForm['breakTo'];

                if(empty($breakFrom) == false || empty($breakTo) == false){
                    if (empty($breakFrom)) {
                        $responseArray['Von']  = 'Es muss ein Datum gesetzt werden.';
                        $response = $response->withStatus(500);
                        return $response->withJSON($responseArray);
                    }
                    if (empty($breakTo)) {
                        $responseArray['Bis']  = 'Es muss ein Datum gesetzt werden.';
                        $response = $response->withStatus(500);
                        return $response->withJSON($responseArray);
                    }
                }

                if(empty($breakFrom) == false && empty($breakTo) == false){
                    
                $breakStartDate = new DateTime($breakFrom);
                $breakEndDate =  new DateTime($breakTo);

                if ($breakEndDate <= $breakStartDate) {
                    $responseArray['Datum']  = 'Das Enddatum muss später als das Startdatum.';
                    $response = $response->withStatus(500);
                    return $response->withJSON($responseArray);
                }
                
                $pause = new Pause();
                $pause->startdate = $breakStartDate;
                $pause->enddate = $breakEndDate;

                $pause->user = $user;
                $this->db->addPause($pause);
                $user->addPause($pause);
                }

            }

            if (array_key_exists('breakId', $userDetailsForm)) {
                $breakId = $userDetailsForm['breakId'];
                $break = $this->db->getPause($breakId);
                $this->db->removePause($break);
            }

            $salutationB = $userDetailsForm['salutationB'];
            $firstnameB = $userDetailsForm['firstnameB'];
            $lastnameB = $userDetailsForm['lastnameB'];
            $streetB = $userDetailsForm['streetB'];
            $plzB = $userDetailsForm['plzB'];
            $cityB = $userDetailsForm['cityB'];
            if (empty($salutationB) && empty($firstnameB) && empty($lastnameB) && empty($streetB) && empty($plzB) && empty($cityB)) {
                if ($user->billingAddress != null) {
                    $billingAddressId = $user->billingAddress->id;
                    $user->setBillingAddress(null);
                    $this->db->removeAddress($billingAddressId);
                }
            } elseif ($user->billingAddress == null) {
                $user->setBillingAddress(new Address());
            }
            if ($user->billingAddress != null) {
                $billingaddress = $user->billingAddress;
                if (is_numeric($salutationB)) {
                    $salutation = $this->db->getSalutation(intval($salutationB));
                    $billingaddress->setSalutation($salutation);
                } else {
                    $billingaddress->setSalutation(null);
                }
                    $billingaddress->firstname = $firstnameB;
                    $billingaddress->name = $lastnameB;
                    $billingaddress->street = $streetB;
                    $billingaddress->zip = $plzB;
                    $billingaddress->town = $cityB;

                if ($billingaddress->id == 0) {
                    $this->db->addAddress($billingaddress);
                }
            }

            $salutationD = $userDetailsForm['salutationD'];
            $firstnameD = $userDetailsForm['firstnameD'];
            $lastnameD = $userDetailsForm['lastnameD'];
            $streetD = $userDetailsForm['streetD'];
            $plzD = $userDetailsForm['plzD'];
            $cityD = $userDetailsForm['cityD'];
            if (empty($salutationD) && empty($firstnameD) && empty($lastnameD) && empty($streetD) && empty($plzD) && empty($cityD)) {
                if ($user->shippingAddress != null) {
                    $shippingAddressId = $user->shippingAddress->id;
                    $user->setShippingAddress(null);
                    $this->db->removeAddress($shippingAddressId);
                }
            } elseif ($user->shippingAddress == null) {
                $user->setShippingAddress(new Address());
            }
            if ($user->shippingAddress != null) {
                $shippingAddress = $user->shippingAddress;
                if (is_numeric($salutationD)) {
                    $salutation = $this->db->getSalutation(intval($salutationD));
                    $shippingAddress->setSalutation($salutation);
                } else {
                    $shippingAddress->setSalutation(null);
                }
                    $shippingAddress->firstname = $firstnameD;
                    $shippingAddress->name = $lastnameD;
                    $shippingAddress->street = $streetD;
                    $shippingAddress->zip = $plzD;
                    $shippingAddress->town = $cityD;

                if ($shippingAddress->id == 0) {
                    $this->db->addAddress($shippingAddress);
                }
            }

            $this->db->save();
        }

        return $response->withJSON($responseArray);
    }

    public function abodetailssave(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $aboDetailsForm = $request->getParsedBody();
        $userId = $args['userid'];
        $articleId = $aboDetailsForm['article'];
        $articletypeId = $aboDetailsForm['articletype'];
        $firstDelivery = $aboDetailsForm['firstDelivery'];
        $wishes = $aboDetailsForm['wishes'];
        $lastDelivery = $aboDetailsForm['lastDelivery'];
        $weekInterval = $aboDetailsForm['weekInterval'];
        $credit = $aboDetailsForm['credit'];
        if ($id != '0') {
            $abo = $this->db->getAbo($id);
        } else {
            $abo = new Abo();
        }
        $articletype = $this->db->getArticletype($articletypeId);
        if (array_key_exists('origin', $aboDetailsForm)) {
            $originId = $aboDetailsForm['origin'];
            $origin = $this->db->getOrigin($originId);
            $abo->setOrigin($origin);
        } else {
            $abo->setOrigin(null);
        }
        $abo->articletype = $articletype;
        if (empty($firstDelivery)) {
            $todayDate = new DateTime();
            $abo->setStartdate(new DateTime($todayDate->format('Y-m-d')));
        }
        else{
            $abo->setStartdate(new DateTime($firstDelivery));
        }

        $abo->setWishes($wishes);
        $abo->setUser($this->db->getUser($userId));
        $abo->setWeekInterwal($weekInterval);
        $abo->setCredit($credit);
        if ($lastDelivery == '') {
            $abo->setEnddate(null);
        } else {
            $abo->setEnddate(new DateTime($lastDelivery));
        }
        if ($id != '0') {
            $this->db->save();
        } else {
            $this->db->addAbo($abo);
        }
    }

    public function abodetails(Request $request, Response $response, $args)
    {
        $data = [];
        if ($args['id'] != '0') {
            $abo = $this->db->getAbo($args['id']);
            $data['abo'] = $abo;
        } else {
            $data['abo'] = null;
        }
        $articles = $this->db->getArticles();
        $origins = $this->db->getOrigins();
        $data['articles'] = $articles;
        $data['defaultArticle'] = $articles[0];
        $data['origins'] = $origins;

        return $this->view->render($response, 'admin/pages/partials/abodetails.twig', $data);
    }

    public function articletypedetails(Request $request, Response $response, $args)
    {
        $data = [];
        if ($args['id'] != '0') {
            $articletype = $this->db->getArticletype($args['id']);
            $data['articletype'] = $articletype;
        } else {
            $data['articletype'] = null;
        }
        $articletypes = $this->db->getArticletypes();
        $data['articletypes'] = $articletypes;
        return $this->view->render($response, 'admin/pages/partials/articletypedetails.twig', $data);
    }

    public function articletypedetailssave(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $articleId = $args['articleId'];
        $articletypeDetailsForm = $request->getParsedBody();
        if ($id != '0') {
            $articletype = $this->db->getArticletype($id);
        } else {
            $articletype = new Articletype();
        }

        $articletype->price = $articletypeDetailsForm['price'];
        $articletype->description = $articletypeDetailsForm['description'];
        if (array_key_exists('onlyAdmin', $articletypeDetailsForm)) {
            $articletype->onlyAdmin = 1;
        } else {
            $articletype->onlyAdmin = 0;
        }
        if (array_key_exists('isActive', $articletypeDetailsForm)) {
            $articletype->isActive = 1;
        } else {
            $articletype->isActive = 0;
        }

        $article = $this->db->getArticle($articleId);
        $articletype->article = $article;
        if ($id != '0') {
            $this->db->save();
        } else {
            $this->db->addArticletype($articletype);
        }
    }

    public function deliverynotes(Request $request, Response $response, $args)
    {
        $regions = $this->db->getRegions();
        $data['regions'] = $regions;
        return $this->view->render($response, 'admin/pages/deliverynotes.twig', $data);
    }

    public function createDeliverynotes(Request $request, Response $response, $args)
    {
        $regionsForm = $request->getParsedBody();

        if (array_key_exists('selectedRegions', $regionsForm)) {
            $selectedRegionIds = array_map('intval', $regionsForm['selectedRegions']);

            $selectedUsers = $this->db->getUsersByRegions($selectedRegionIds);

            $pdf = new FPDF('P', 'cm', 'A5');
            $defaultLineHeight = 0.55;
            $defFontSize = 11.5;
            $fontFamily = 'Arial';
            $todayDate = new DateTime();
            $todayDate = new DateTime($todayDate->format('Y-m-d'));

            foreach ($selectedUsers as $userIndex => $user) {
                if ($user->region !== null && $user->isActive) {
                    foreach ($user->abos as $aboIndex => $abo) {
                        if ($abo->startdate <= $todayDate && ($abo->enddate == null || $abo->enddate >= $todayDate)) {
                            $pdf->AddPage();
                            $pdf->SetFont($fontFamily, '', $defFontSize);
                            $x = $pdf->GetX();
                            $y = $pdf->GetY();
                            $pdf->SetXY($x + 7.5, $y);
                            $pdf->Image(getenv('GEMEGGLI_LOGO_PATH'));
                            $gemeggliAddress = utf8_decode(str_replace('"', '', str_replace('\n', chr(10), getenv('GEMEGGLI_ADDRESS'))));
                            $pdf->Multicell(0, $defaultLineHeight, $gemeggliAddress, 0, 'L');
                            $pdf->SetXY($x, $y);
                            $pdf->SetFont($fontFamily, 'B', $defFontSize);
                            $pdf->Multicell(0, $defaultLineHeight, 'Kundenadresse', 0, 'L');
                            $pdf->SetFont($fontFamily, '', $defFontSize);
                            $pdf->Multicell(7, $defaultLineHeight, utf8_decode($user->getAddressForDeliverynote()), 0, 'L');
            
                            $x = $pdf->GetX();
                            $y = $pdf->GetY();
                            $pdf->SetXY($x, $y+0.2);
                            $pdf->SetFont($fontFamily, 'B', $defFontSize);
                            $pdf->Multicell(0, $defaultLineHeight, 'Guthaben', 0, 'L');
                            $pdf->SetFont($fontFamily, '', $defFontSize);
                            $pdf->Multicell(7, $defaultLineHeight, utf8_decode($abo->credit), 0, 'L');
    
                            $x = $pdf->GetX();
                            $y = $pdf->GetY();
                            $pdf->SetXY($x, $y+0.2);
                            $pdf->SetFont($fontFamily, 'B', $defFontSize);
                            $pdf->Multicell(0, $defaultLineHeight, utf8_decode('Unterbrechung'), 0, 'L');
                            $pdf->SetFont($fontFamily, '', $defFontSize);
                            $pdf->Multicell(0, $defaultLineHeight, $user->getPauseFormated(), 0, 'L');
    
                            $x = $pdf->GetX();
                            $y = 8.7;
                            $pdf->SetXY($x, $y+0.2);
                            $pdf->SetFont($fontFamily, 'B', $defFontSize);
                            $pdf->Multicell(0, $defaultLineHeight, utf8_decode($abo->articletype->article->name .' für CHF '.$abo->articletype->price), 0, 'L');
    
                            $x = $pdf->GetX();
                            $y = $pdf->GetY();
                            $pdf->SetXY($x, $y+0.2);
                            $pdf->SetFont($fontFamily, 'B', $defFontSize);
                            $pdf->Multicell(0, $defaultLineHeight, utf8_decode('Wünsche'), 0, 'L');
                            $pdf->SetFont($fontFamily, '', $defFontSize);
                            $pdf->Multicell(13, $defaultLineHeight, utf8_decode(empty($abo->wishes) ? '-' : $abo->wishes), 0, 'L');

                            if($abo->articletype->article->hasOrigin){
                                $x = $pdf->GetX();
                                $y = $pdf->GetY();
                                $pdf->SetXY($x, $y+0.2);
                                $pdf->SetFont($fontFamily, 'B', $defFontSize);
                                $pdf->Multicell(0, $defaultLineHeight, utf8_decode('Herkunft'), 0, 'L');
                                $pdf->SetFont($fontFamily, '', $defFontSize);
                                $pdf->Multicell(0, $defaultLineHeight, utf8_decode($abo->origin->name), 0, 'L');
                            }

                            $x = $pdf->GetX();
                            $y = $pdf->GetY();
                            $pdf->SetXY($x, $y+0.2);
                            $pdf->SetFont($fontFamily, 'B', $defFontSize);
                            $pdf->Multicell(0, $defaultLineHeight, utf8_decode('Inhalt'), 0, 'L');
                            $pdf->SetFont($fontFamily, '', $defFontSize);
                            $pdf->Multicell(0, $defaultLineHeight, utf8_decode($abo->articletype->description), 0, 'L');
                        }
                    }
                }
            }
            $pdf->Output('Lieferscheine.pdf', 'I');
        }
        return $response->withHeader('Content-type', 'application/pdf');
    }

    public function bills(Request $request, Response $response, $args)
    {
        $data = [];
        return $this->view->render($response, 'admin/pages/bills.twig', $data);
    }
}
