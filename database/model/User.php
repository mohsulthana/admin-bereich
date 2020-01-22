<?php
namespace Gemueseeggli\Database\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Gemueseeggli\Database\Model\Region;
use Gemueseeggli\Database\Model\BaseModel;
use Gemueseeggli\Database\Model\Salutation;
use Gemueseeggli\Database\Model\Address;
use \DateTime;

require_once('BaseModel.php');
/**
 * @Entity @Table(name="users")
 */
class User extends BaseModel
{
    /**
     * @Column(type="string")
     * @var string
     **/
    public $username;
    /**
     * @Column(type="string", nullable=true)
     * @var string
     **/
    protected $password;
    /**
     * @Column(type="boolean")
     * @var boolean
     **/
    public $isAdmin;
    /**
     * @Column(type="boolean")
     * @var boolean
     **/
     public $isActive;
    /**
     * @ManyToOne(targetEntity="Region", inversedBy="assignedUsers", fetch="EAGER")
     * @JoinColumn(name="region_id", referencedColumnName="id", nullable=true)
     **/
    public $region;
    /**
     * @OneToMany(targetEntity="Abo", mappedBy="user", cascade={"persist"})
     */
    public $abos;
    /**
     * @OneToMany(targetEntity="Pause", mappedBy="user", cascade={"persist"})
     */
    public $pauses;
    /**
     * @Column(type="string", nullable=true)
     * @var string
     **/
    public $passwordresetcode;
    /**
     * @Column(type="datetime", nullable=true)
     * @var datetime
     **/
    public $passwordresetdate;
    /**
     * @OneToOne(targetEntity="Address", fetch="EAGER")
     * @JoinColumn(name="billingAddress_id", referencedColumnName="id", nullable=true)
     */
    public $billingAddress;
    /**
     * @OneToOne(targetEntity="Address", fetch="EAGER")
     * @JoinColumn(name="shippingAddress_id", referencedColumnName="id", nullable=true)
     */
    public $shippingAddress;

    public function __construct()
    {
        $this->abos = new ArrayCollection();
        $this->pauses = new ArrayCollection();
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }

    public function getRegion()
    {
        return $this->region;
    }

    public function getSortValue()
    {
        if ($this->billingAddress == null) {
            return strtolower($this->username);
        }
        return strtolower($this->billingAddress->getFullname());
    }

    public function setRegion($region)
    {
        $this->region = $region;
    }

    public function getPasswordresetcode()
    {
        return $this->passwordresetcode;
    }

    public function setPasswordresetcode($passwordresetcode)
    {
        $this->passwordresetcode = $passwordresetcode;
    }

    public function getPasswordresetdate()
    {
        return $this->passwordresetdate;
    }

    public function setPasswordresetdate($passwordresetdate)
    {
        $this->passwordresetdate = $passwordresetdate;
    }

    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    public function setBillingAddress($billingAddress)
    {
        $this->billingAddress = $billingAddress;
    }

    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress($shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
    }

    public function withoutPassword()
    {
        $this->password = '';
        return $this;
    }

    public function addAbo($abo)
    {
        $this->abos[] = $abo;
    }

    public function getAbos()
    {
        return $this->abos;
    }

    public function addPause($pause)
    {
        $this->pauses[] = $pause;
    }

    public function getAddressForDeliverynote()
    {
        if ($this->shippingAddress !== null) {
            return $this->shippingAddress->getAsString();
        }
        if ($this->billingAddress !== null) {
            return $this->billingAddress->getAsString();
        }
        return $this->username;
    }

    public function getPauseFormated()
    {
        foreach ($this->pauses as $pauseIndex => $pause) {
            $todayDate = new DateTime();
            $todayDate = new DateTime($todayDate->format('Y-m-d'));
            if ($pause->enddate >= $todayDate) {
                return $pause->startdate->format('d.m.Y') . ' - '.$pause->enddate->format('d.m.Y');
            }
        }
        return 'Keine';
    }
}
