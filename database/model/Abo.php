<?php

namespace Gemueseeggli\Database\Model;

use Gemueseeggli\Database\Model\BaseModel;
use Gemueseeggli\Database\Model\Origin;
use Gemueseeggli\Database\Model\Article;
use \Datetime;

require_once('BaseModel.php');
/**
 * @Entity @Table(name="abos")
 */
class Abo extends BaseModel
{
    /**
     * @ManyToOne(targetEntity="Articletype", inversedBy="assignedAbos")
     * @JoinColumn(name="articletype_id", referencedColumnName="id")
     **/
    public $articletype;
    /**
     * @Column(type="integer")
     * @var integer
     **/
    public $weekInterval;
    /**
     * @Column(type="string", nullable=true)
     * @var string
     **/
    public $wishes;
    /**
     * @ManyToOne(targetEntity="Origin", inversedBy="assignedAbos", fetch="EAGER")
     * @JoinColumn(name="origin_id", referencedColumnName="id", nullable=true)
     **/
    public $origin;
    /**
     * @Column(type="datetime")
     * @var datetime
     **/
    public $startdate;

     /**
     * @ManyToOne(targetEntity="User", inversedBy="abos")
     * @JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    public $user;
    /**
     * @Column(type="datetime", nullable=true)
     * @var datetime
     **/
    public $enddate;
    /**
     * @Column(type="integer")
     * @var integer
     **/
    public $credit;

    public function getWeekInterval()
    {
        return $this->weekInterval;
    }

    public function setWeekInterwal($weekInterval)
    {
        $this->weekInterval = $weekInterval;
    }

    public function getWishes()
    {
        return $this->wishes;
    }

    public function setWishes($wishes)
    {
        $this->wishes = $wishes;
    }

    public function getOrigin()
    {
        return $this->origin;
    }

    public function setOrigin($origin)
    {
        $this->origin = $origin;
    }
    
    public function getStartdate()
    {
        return $this->startdate;
    }

    public function getCredit()
    {
        return $this->credit;
    }

    public function setCredit($credit)
    {
        $this->credit = $credit;
    }

    public function getFormatedStartdate()
    {
        return  date_format($this->getStartdate(), 'd.m.Y');
    }

    public function setStartdate($startdate)
    {
        $this->startdate = $startdate;
    }

    public function getEnddate()
    {
        return $this->enddate;
    }

    public function getFormatedEnddate()
    {
        if ($this->getEnddate() == null) {
            return '';
        }
        return  date_format($this->getEnddate(), 'd.m.Y');
    }

    public function setEnddate($enddate)
    {
        $this->enddate = $enddate;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getIsActive()
    {
        if ($this->enddate == null) {
            return true;
        }
        if (new DateTime() < $this->enddate) {
            return true;
        }
        return false;
    }
}
