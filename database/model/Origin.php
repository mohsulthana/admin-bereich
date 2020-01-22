<?php
namespace Gemueseeggli\Database\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Gemueseeggli\Database\Model\Abo;
use Gemueseeggli\Database\Model\BaseModel;

require_once('BaseModel.php');
/**
 * @Entity @Table(name="origins")
 */
class Origin extends BaseModel
{
    /**
     * @Column(type="string")
     **/
    public $name;
    /**
     * @OneToMany(targetEntity="Abo", mappedBy="origin")
     */
    protected $assignedAbos;

    public function __construct()
    {
        $this->assignedAbos = new ArrayCollection();
    }

    public function assignToUser($abo)
    {
        $this->assignedAbos[] = $abo;
    }

    public function getAssignedAbos()
    {
        return $this->assignedAbos;
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
