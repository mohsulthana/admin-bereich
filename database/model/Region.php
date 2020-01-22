<?php
namespace Gemueseeggli\Database\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Gemueseeggli\Database\Model\User;

require_once('BaseModel.php');
/**
 * @Entity @Table(name="regions")
 */
class Region extends BaseModel
{
    /**
     * @Column(type="string")
     **/
    public $name;
    /**
     * @OneToMany(targetEntity="User", mappedBy="region")
     */
    public $assignedUsers;

    public function __construct()
    {
        $this->assignedUsers = new ArrayCollection();
    }

    public function assignToUser($user)
    {
        $this->assignedUsers[] = $user;
    }

    public function getAssignedUsers()
    {
        return $this->assignedUsers;
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
