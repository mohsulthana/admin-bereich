<?php
namespace Gemueseeggli\Database\Model;

use Gemueseeggli\Database\Model\Salutation;
use Gemueseeggli\Database\Model\Region;
use Gemueseeggli\Database\Model\User;
use Gemueseeggli\Database\Model\BaseModel;

require_once('BaseModel.php');
/**
 * @Entity @Table(name="addresses")
 */
class Address extends BaseModel
{
    /**
     * @ManyToOne(targetEntity="Salutation", inversedBy="assignedAddresses", fetch="EAGER")
     * @JoinColumn(name="salutation_id", referencedColumnName="id", nullable=false)
     */
    public $salutation;
    /**
     * @Column(type="string")
     * @var string
     **/
    public $firstname;
    /**
     * @Column(type="string")
     * @var string
     **/
    public $name;
    /**
     * @Column(type="string")
     * @var string
     **/
    public $street;
    /**
     * @Column(type="integer")
     **/
    public $zip;
    /**
     * @Column(type="string")
     * @var string
     **/
    public $town;


    public function getSalutation()
    {
        return $this->salutation;
    }

    public function setSalutation($salutation)
    {
        $this->salutation = $salutation;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getFullname()
    {
        return $this->name.', '.$this->firstname;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getStreet()
    {
        return $this->street;
    }

    public function setStreet($street)
    {
        $this->street = $street;
    }

    public function getZip()
    {
        return $this->zip;
    }

    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    public function getTown()
    {
        return $this->town;
    }

    public function setTown($town)
    {
        $this->town = $town;
    }
    
    public function getAsString()
    {
        $formatedAddress = $this->salutation->name;
        $formatedAddress = $formatedAddress . chr(10) . $this->firstname .' '. $this->name;
        $formatedAddress = $formatedAddress . chr(10) . $this->street;
        $formatedAddress = $formatedAddress . chr(10) . $this->zip .' '. $this->town;
        return $formatedAddress;
    }
}
