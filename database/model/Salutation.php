<?php
namespace Gemueseeggli\Database\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Gemueseeggli\Database\Model\Address;
use Gemueseeggli\Database\Model\User;
use Gemueseeggli\Database\Model\Region;

require_once('BaseModel.php');
/**
 * @Entity @Table(name="salutations")
 */
class Salutation extends BaseModel
{
    /**
     * @Column(type="string")
     **/
    public $name;
    /**
     * @OneToMany(targetEntity="Address", mappedBy="salutation")
     */
    public $assignedAddresses;

    public function __construct() {
        $this->assignedAddresses = new ArrayCollection();
    }

    public function assignToAddresses($address)
    {
        $this->assignedAddresses[] = $address;
    }

    public function getAssignedAddresses()
    {
        return $this->assignedAddresses;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}