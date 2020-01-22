<?php
namespace Gemueseeggli\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Gemueseeggli\Database\Model\User;
use Gemueseeggli\Database\Model\Region;
use Gemueseeggli\Database\Model\Abo;
use Gemueseeggli\Database\Model\Article;
use Gemueseeggli\Database\Model\Origin;
use Gemueseeggli\Database\Model\Address;
use Gemueseeggli\Database\Model\Pause;
use \Datetime;

/**
 * Class ApiController
 * @package Gemueseeggli\Controller
 */
class ApiController extends AbstractController
{
    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function getArticles(Request $request, Response $response, $args)
    {
        $articles = $this->db->getArticles();
        return $response->withJSON($articles);
    }

    public function getArticle(Request $request, Response $response, $args)
    {
        $article = $this->db->getArticle($args['id']);
        $article->assignedArticletypes = $article->assignedArticletypes->getValues();
        foreach ($article->assignedArticletypes as $articletypeIndex => $articletype) {
            $articletype->article = null;
        }
        return $response->withJSON($article);
    }

    public function getUsers(Request $request, Response $response, $args)
    {
        $queryParams = $request->getQueryParams();
        foreach ($queryParams as $key => $param) {
            if ($key == 'Top') {
                $top = $param;
            }
            if ($key == 'Skip') {
                $skip = $param;
            }
            if ($key == 'Title') {
                $title = $param;
            }
            if ($key == 'OrderBy') {
                $orderBy = $param;
            }
            if ($key == 'OrderByDesc') {
                $orderByDesc = $param;
            }
        }
        $users = $this->db->getUsers($top, $skip, $title, $orderBy, $orderByDesc);
        $data = [];
        $data['draw'] = 0;
        $data['recordsTotal'] = count($this->db->getAllUsers());
        $data['recordsFiltered'] = count($users);

        $users = array_slice ($users, $skip, $top);
        $data['data'] = $users;
        return $response->withJSON($data);
    }

    public function getAbos(Request $request, Response $response, $args)
    {
        $abos = $this->db->getAbosByUserId($args['id']);
        foreach ($abos as $key => $abo) {
            $dummy = $abo->articletype->article->name;
        }
        return $response->withJSON($abos);
    }

    public function getArticletypes(Request $request, Response $response, $args)
    {
        $articletypes = $this->db->getArticletypesByArticleId($args['id']);
        return $response->withJSON($articletypes);
    }

    public function createAbo(Request $request, Response $response, $args)
    {
        $createAboForm = $request->getParsedBody();
        $currentUser = $this->db->getUser($this->user->getId());

        if(empty($currentUser)) {
            $responseArray['Fehler']  = 'Es ist ein unerwarteter Fehler aufgetreten.';
            $response = $response->withStatus(500);
            return $response->withJSON($responseArray);
        }

        $abo = new Abo();
        $abo->articletype = $this->db->getArticletype($createAboForm['articletype']);
        $abo->setWeekInterwal(2);
        if (array_key_exists('origin', $createAboForm)) {
            $abo->setOrigin($this->db->getOrigin($createAboForm['origin']));
        }
        $abo->weekInterval = $createAboForm['aboInterval'];
        $abo->setStartdate(new DateTime($createAboForm['startdate']));
        $abo->article = $this->db->getArticle($createAboForm['article']);
        $abo->setWishes($createAboForm['wishes']);
        $abo->setUser($currentUser);
        $abo->setCredit(0);
        $this->db->addAbo($abo);
        $this->db->save();

        $this->mail->checkoutAboMail($currentUser, $abo);

        $responseArray = ["answer" => 'Das Abo wurde erfolgreich abgeschlossen.'];
        return $response->withJSON($responseArray);
    }

    public function quitAbo(Request $request, Response $response, $args)
    {
        $quitAboForm = $request->getParsedBody();
        $aboid = $quitAboForm['aboId'];
        $aboEndDateText = $quitAboForm['aboEndDate'];
        $aboEndDate = new DateTime($aboEndDateText);
        $aboQuitReason = $quitAboForm['quitReason'];

        if(empty($aboEndDateText)) {
            $responseArray['Fehler']  = 'Es muss ein Kündigungsdatum angegeben werden!';
            $response = $response->withStatus(500);
            return $response->withJSON($responseArray);
        }

        $abo = $this->db->getAbo($aboid);
        $abo->enddate = $aboEndDate;

        $this->db->save();
        $currentUser = $this->db->getUser($this->user->getId());
        $this->mail->deleteAboMail($currentUser, $abo, $aboQuitReason);

        $responseArray = ["answer" => "Ihr Abo wurde erfolgreich gekündet."];
        return $response->withJSON($responseArray);
    }

    public function updateAbo(Request $request, Response $response, $args)
    {
        $aboUpdateForm = $request->getParsedBody();
        $aboid = $aboUpdateForm['id'];
        $originid = null;
        $aboorigin = null;
        if(array_key_exists('aboOrigin', $aboUpdateForm)) {
            $originid = $aboUpdateForm['aboOrigin'];;
            $aboorigin = $this->db->getOrigin($originid);
        } 
        $abointerval = $aboUpdateForm['aboInterval'];
        $abowishes = $aboUpdateForm['aboWishes'];
        $currentUser = $this->db->getUser($this->user->id);

        $abo = $this->db->getAbo($aboid);
        if(empty($abo)) {
            $responseArray['Fehler']  = 'Es ist ein unerwarteter Fehler aufgetreten.';
            $response = $response->withStatus(500);
            return $response->withJSON($responseArray);
        }
        if(!($abo->user->id == $currentUser->id || $currentUser->isAdmin)) {
            $responseArray['Fehler']  = 'Es ist ein unerwarteter Fehler aufgetreten.';
            $response = $response->withStatus(500);
            return $response->withJSON($responseArray);
        }

        $aboChanged = false;
        if($abo->origin != $aboorigin) {
            $abo->origin = $aboorigin;
            $aboChanged = true;
        }
        if($abo->weekInterval != $abointerval) {
            $abo->weekInterval = $abointerval;
            $aboChanged = true;
        }
        if($abo->wishes != $abowishes) {
            $abo->wishes = $abowishes;
            $aboChanged = true;
        }
        if($aboChanged) {
            $this->db->save();
            $this->mail->aboUpdateMail($currentUser, $abo);
        }

        $responseArray = ["answer" => "Änderungen wurden gespeichert."];
        return $response->withJSON($responseArray);
    }

    public function imageupload(Request $request, Response $response, $args)
    {
        $imageUploadForm = $request->getParsedBody();

        $articleId = $imageUploadForm['articleId'];

        $article = $this->db->getArticle($articleId);

        if ($article != null) {
            $directory = $_SERVER['DOCUMENT_ROOT'].'\..\public\img\articles';
            
                    $uploadedFiles = $request->getUploadedFiles();
                    
                    $uploadedFile = $uploadedFiles['article-image-file-input'];
            
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $filePath = $directory . '\\'. $article->imagepath;
                $uploadedFile->moveTo($filePath);
            }
        }

        $responseArray = ["answer" => "Anzeigebild erfolgreich ausgetauscht!"];
        return $response->withJSON($responseArray);
    }
    
    public function createPause(Request $request, Response $response, $args)
    {
        $breakForm = $request->getParsedBody();
        $breakFrom = $breakForm['breakFrom'];
        $breakTo = $breakForm['breakTo'];
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

        $breakStartDate = new DateTime($breakFrom);
        $breakEndDate =  new DateTime($breakTo);
        
        $currentUser = $this->db->getUser($this->user->id);
        $pause = new Pause();
        $pause->startdate = $breakStartDate;
        $pause->enddate = $breakEndDate;

        if ($breakEndDate <= $breakStartDate) {
            $responseArray['Datum']  = 'Das Enddatum muss später als das Startdatum.';
            $response = $response->withStatus(500);
            return $response->withJSON($responseArray);
        }
        
        $pause->user = $currentUser;
        $this->db->addPause($pause);
        $currentUser->addPause($pause);

        $this->db->save();

        $this->mail->breakCreateMail($currentUser);   
        
        $responseArray['response']  = 'Unterbrechug erfolgreich eingerichtet.';
        return $response->withJSON($responseArray);
    }

    public function deletePause(Request $request, Response $response, $args)
    {
        $breakId = $args['id'];
        $responseArray['response']  = 'Unterbrechung erfolgreich gelöscht.';
        $break = $this->db->getPause($breakId);
        $currentUser = $this->db->getUser($this->user->id);

        if ($break == null || $currentUser == null  || ($break->user->id != $currentUser->id && $currentUser->isAdmin == false)) {
            $responseArray['Fehler']  = 'Ein unerwarteter Fehler ist aufgetreten!';
            $response = $response->withStatus(500);
            return $response->withJSON($responseArray);
        }

        $this->mail->breakDeleteMail($currentUser);    

        $this->db->removePause($break);
        return $response->withJSON($responseArray);
    }

    public function updateAddresses(Request $request, Response $response, $args)
    {
        $addressesForm = $request->getParsedBody();
        $salutationA = $addressesForm['salutationA'];
        $firstnameA = $addressesForm['firstnameA'];
        $lastnameA = $addressesForm['lastnameA'];
        $streetA = $addressesForm['streetA'];
        $plzA = $addressesForm['plzA'];
        $cityA = $addressesForm['cityA'];

        $salutationB = '';
        $firstnameB = '';
        $lastnameB = '';
        $streetB = '';
        $plzB = '';

        $hasSecondAddress = false;

        if (empty($firstnameA) || empty($lastnameA) || empty($streetA) || empty($plzA)) {
            $responseArray['Rechnungsadresse']  = 'Es müssen alle Felder ausgefüllt werden!';
            $response = $response->withStatus(500);
            return $response->withJSON($responseArray);
        }

        $currentUser = $this->db->getUser($this->user->id);
        $billingaddress = $currentUser->billingAddress;
        $shippingaddress = $currentUser->shippingAddress;

        $changes = '';

        if (array_key_exists('hasShippingAddress', $addressesForm)) {
            $salutationB = $addressesForm['salutationB'];
            $firstnameB = $addressesForm['firstnameB'];
            $lastnameB = $addressesForm['lastnameB'];
            $streetB = $addressesForm['streetB'];
            $plzB = $addressesForm['plzB'];
            $cityB = $addressesForm['cityB'];

            $hasSecondAddress = true;
            
            if (empty($firstnameB) || empty($lastnameB) || empty($streetB) || empty($plzB)) {
                $responseArray['Lieferadresse']  = 'Es müssen alle Felder ausgefüllt werden!';
                $response = $response->withStatus(500);
                return $response->withJSON($responseArray);
            }
        } else {
            if (!is_null($shippingaddress)) {
                if(!is_Null($currentUser->shippingAddress)) { 
                    $currentUser->shippingAddress = null; 
                    $this->db->removeAddress($shippingaddress->id);
                    $changes .= 'Die Lieferadresse wurde entfernt. ';
                }
            }
        }

        $addBillingaddress = false;
        if (is_null($billingaddress)) {
            $billingaddress = new Address();
            $addBillingaddress = true;
            $changes .= 'Die Rechnungsadresse wurde erfasst. ';
        }

        $hasBillingaddressChanges = false;
        $hasShippingaddressChanges = false;
        $salutationAObj = $this->db->getSalutation($salutationA);
        if($salutationAObj->id != $billingaddress->salutation->id) {
            $billingaddress->salutation = $salutationAObj;
            $hasBillingaddressChanges = true;
        }
        if($billingaddress->firstname != $firstnameA){
            $billingaddress->firstname = $firstnameA;
            $hasBillingaddressChanges = true;
        }
        if($billingaddress->name != $lastnameA){
            $billingaddress->name = $lastnameA;
            $hasBillingaddressChanges = true;
        }
        if($billingaddress->street != $streetA){
            $billingaddress->street = $streetA;
            $hasBillingaddressChanges = true;
        }
        if($billingaddress->zip != $plzA){
            $billingaddress->zip = $plzA;
            $hasBillingaddressChanges = true;
        }        
        if($billingaddress->town != $cityA){
            $billingaddress->town = $cityA;
            $hasBillingaddressChanges = true;
        }
        

        $addShippingaddress = false;
        if ($hasSecondAddress) {
            if (is_null($shippingaddress)) {
                $shippingaddress = new Address();
                $addShippingaddress = true;
                $changes .= 'Die Lieferadresse wurde erfasst. ';
            } 
            $salutationBObj = $this->db->getSalutation($salutationB);
            if(is_null($shippingaddress->salutation) || $salutationBObj->id != $shippingaddress->salutation->id) {
                $shippingaddress->salutation = $salutationBObj;
                $hasShippingaddressChanges = true;
            }
            if($shippingaddress->firstname != $firstnameB){
                $shippingaddress->firstname = $firstnameB;
                $hasShippingaddressChanges = true;
            }
            if($shippingaddress->name != $lastnameB){
                $shippingaddress->name = $lastnameB;
                $hasShippingaddressChanges = true;
            }
            if($shippingaddress->street != $streetB){
                $shippingaddress->street = $streetB;
                $hasShippingaddressChanges = true;
            }
            if($shippingaddress->zip != $plzB){
                $shippingaddress->zip = $plzB;
                $hasShippingaddressChanges = true;
            }        
            if($shippingaddress->town != $cityB){
                $shippingaddress->town = $cityB;
                $hasShippingaddressChanges = true;
                }
        }

        if ($addBillingaddress) {
            $this->db->addAddress($billingaddress);
            $currentUser->billingAddress = $billingaddress;
        }
        if ($addShippingaddress) {
            $this->db->addAddress($shippingaddress);
            $currentUser->shippingAddress = $shippingaddress;
        }
        $this->db->save();

        if($changes == '') {
            if($hasBillingaddressChanges || $hasShippingaddressChanges)
            {
                $changes = 'Es wurden Änderungen an einer bestehenden Adresse vorgenommen.';
                $this->mail->addressChangeMail($currentUser, $changes);    
            }
        }
        else {
            $this->mail->addressChangeMail($currentUser, $changes);        
        }

        $responseArray['answer']  = 'Ihre Adressänderung war erfolgreich.';
        return $response->withJSON($responseArray);
    }

    public function contact(Request $request, Response $response, $args)
    {
        $contactForm = $request->getParsedBody();

        $name = $contactForm['name'];
        $email = $contactForm['email'];
        $message = $contactForm['message'];
        $human = $contactForm['human'];
        $humanCheck = $contactForm['humanCheck'];

        $responseArray = [];

        if ($human != $humanCheck) {
            $responseArray['Kontrollzahl']  = 'Die Kontrollzahl ist nicht korrekt!';
            $response = $response->withStatus(500);
        } else {
            if ($this->isEmail($email)) {
                if ($message != '') {
                    $this->mail->contactMail($name, $email, $message);
                    $responseArray["message"] = "<strong>Versendet!</strong> Ihre Anfrage wure erfolgreich versendet und wird von uns schnellstmöglich beantwortet!";
                } else {
                    $responseArray['Nachricht']  = 'Bitte füllen Sie das Nachrichtenfeld aus!';
                    $response = $response->withStatus(500);
                }
            } else {
                $responseArray['E-Mail']  = 'Das ist keine gültige E-Mail Adresse!';
                $response = $response->withStatus(500);
            }
        }
        return $response->withJSON($responseArray);
    }
}
