<?php
namespace Gemueseeggli\Database\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Gemueseeggli\Database\Model\BaseModel;
use Gemueseeggli\Database\Model\Article;

require_once('BaseModel.php');
/**
 * @Entity @Table(name="prices")
 */
class Price extends BaseModel
{
    /**
     * @Column(type="decimal", precision=6, scale=2)
     * @var integer
     **/
    public $price;
    /**
     * @Column(type="boolean")
     * @var boolean
     **/
    public $onlyAdmin;
    /**
     * @OneToMany(targetEntity="Abo", mappedBy="price")
     */
    protected $assignedAbos;

    public function __construct()
    {
        $this->assignedAbos = new ArrayCollection();
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }
}
