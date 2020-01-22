<?php

namespace Gemueseeggli\Database;

use Gemueseeggli\Database\Model;
use Gemueseeggli\Database\Model\User;

require_once(__DIR__ . '/../database/model/Region.php');
require_once(__DIR__ . '/../database/model/User.php');
require_once(__DIR__ . '/../database/model/Salutation.php');
require_once(__DIR__ . '/../database/model/Address.php');
require_once(__DIR__ . '/../database/model/Abo.php');
require_once(__DIR__ . '/../database/model/Article.php');
require_once(__DIR__ . '/../database/model/Origin.php');
require_once(__DIR__ . '/../database/model/Price.php');
require_once(__DIR__ . '/../database/model/Pause.php');
require_once(__DIR__ . '/../database/model/Articletype.php');
require_once(__DIR__ . '/../database/queries.php');

class DBConnector
{

    // Vars
    var $em;

    public function __construct($c)
    {
        $this->em = $c->get('em');
    }

    public function addUser($username, $password)
    {
        $user = new User();
        $user->setUsername($username);
        if ($password != null && !empty($password)) {
            $user->setPassword(md5($password));
        }
        $user->setIsAdmin(false);
        $user->isActive = true;

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
    
    public function authenticateUser($username, $password)
    {
        $userRepository = $this->em->getRepository('Gemueseeggli\Database\Model\User');
        $user = $userRepository->findOneBy(['username' => $username, 'password' => md5($password)]);

        return $user;
    }

    public function getUserByUsername($username)
    {
        $userRepository = $this->em->getRepository('Gemueseeggli\Database\Model\User');
        $user = $userRepository->findOneBy(['username' => $username]);

        return $user;
    }

    public function getUsersByRegions($regionIds)
    {
        $userRepository = $this->em->getRepository('Gemueseeggli\Database\Model\User');
        $user = $userRepository->findBy(['region' => $regionIds]);

        return $user;
    }

    public function getUserByHash($hash)
    {
        $userRepository = $this->em->getRepository('Gemueseeggli\Database\Model\User');
        $user = $userRepository->findOneBy(['passwordresetcode' => $hash]);

        return $user;
    }

    public function getAllUsers()
    {
        $userRepository = $this->em->getRepository('Gemueseeggli\Database\Model\User');
        return $userRepository->findAll();
    }

    public function getUsers($top, $skip, $title, $orderBy, $orderByDesc)
    {
        $queries = new Queries();
        $users = $queries->getUsers($this->em, $top, $skip, $title, $orderBy, $orderByDesc);

        return $users;
    }

    public function getAllBills()
    {
        $userRepository = $this->em->getRepository('Gemueseeggli\Database\Model\Bill');
        return $userRepository->findAll();
    }

    public function getBills($top, $skip, $title, $orderBy, $orderByDesc)
    {
        $queries = new Queries();
        $bills = $queries->getBills($this->em, $top, $skip, $title, $orderBy, $orderByDesc);

        return $bills;
    }

    public function getUser($id)
    {
        $userRepository = $this->em->getRepository('Gemueseeggli\Database\Model\User');
        $user = $userRepository->find($id);

        return $user;
    }

    public function deleteUser($id)
    {
        $user = $this->getUser(id);
        $this->em->remove($user);
        $this->save();
    }
    


    //Global save method for all entity types
    public function save()
    {
        $this->em->flush();
    }

    public function getAbosByUserId($userid)
    {
        $aboRepository = $this->em->getRepository('Gemueseeggli\Database\Model\Abo');
        $abos = $aboRepository->findBy(['user' => $userid]);

        return $abos;
    }

    public function getAbo($id)
    {
        $aboRepository = $this->em->getRepository('Gemueseeggli\Database\Model\Abo');
        $abo = $aboRepository->find($id);

        return $abo;
    }

    public function addAbo($abo)
    {
        $this->em->persist($abo);
        $this->em->flush();

        return $abo;
    }

    public function addArticletype($articletype)
    {
        $this->em->persist($articletype);
        $this->em->flush();
        return $articletype;
    }

    public function getArticles()
    {
        $articleRepository = $this->em->getRepository('Gemueseeggli\Database\Model\Article');
        $articles = $articleRepository->findAll();

        return $articles;
    }

    public function getArticletypesByArticleId($articleId)
    {
        $articletypeRepository = $this->em->getRepository('Gemueseeggli\Database\Model\Articletype');
        $articletypes = $articletypeRepository->findBy(['article' => $articleId]);

        return $articletypes;
    }

    public function getArticletypes()
    {
        $articletypeRepository = $this->em->getRepository('Gemueseeggli\Database\Model\Articletype');
        $articletypes = $articletypeRepository->findAll();

        return $articletypes;
    }

    public function getArticle($id)
    {
        $articleRepository = $this->em->getRepository('Gemueseeggli\Database\Model\Article');
        $article = $articleRepository->find($id);

        return $article;
    }

    public function getOrigin($id)
    {
        $originRepository = $this->em->getRepository('Gemueseeggli\Database\Model\Origin');
        $origin = $originRepository->find($id);

        return $origin;
    }

    public function getArticletype($id)
    {
        $articletypeRepository = $this->em->getRepository('Gemueseeggli\Database\Model\Articletype');
        $articletype = $articletypeRepository->find($id);

        return $articletype;
    }

    public function getOrigins()
    {
        $originRepository = $this->em->getRepository('Gemueseeggli\Database\Model\Origin');
        $origins = $originRepository->findAll();

        return $origins;
    }

    public function getRegions()
    {
        $regionRepository = $this->em->getRepository('Gemueseeggli\Database\Model\Region');
        $regions = $regionRepository->findAll();

        return $regions;
    }

    public function getRegion($id)
    {
        $regionRepository = $this->em->getRepository('Gemueseeggli\Database\Model\Region');
        $region = $regionRepository->find($id);

        return $region;
    }

    public function getSalutations()
    {
        $salutationRepository = $this->em->getRepository('Gemueseeggli\Database\Model\Salutation');
        $salutations = $salutationRepository->findAll();

        return $salutations;
    }

    public function getSalutation($id)
    {
        $salutationRepository = $this->em->getRepository('Gemueseeggli\Database\Model\Salutation');
        $salutation = $salutationRepository->find($id);

        return $salutation;
    }

    public function getAddress($id)
    {
        $addressRepository = $this->em->getRepository('Gemueseeggli\Database\Model\Address');
        $address = $addressRepository->find($id);

        return $address;
    }

    public function addAddress($address)
    {
        $this->em->persist($address);
        $this->em->flush();

        return $address;
    }

    public function removeAddress($id)
    {
        $address = $this->getAddress($id);
        $this->em->remove($address);
        $this->save();
    }

    public function getPause($id)
    {
        $pauseRepository = $this->em->getRepository('Gemueseeggli\Database\Model\Pause');
        $pause = $pauseRepository->find($id);

        return $pause;
    }
    
    public function addPause($pause)
    {
        $this->em->persist($pause);
        $this->em->flush();

        return $pause;
    }

    public function removePause($id)
    {
        $pause = $this->getPause($id);
        $this->em->remove($pause);
        $this->save();
    }
}
