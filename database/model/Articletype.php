<?php

namespace Gemueseeggli\Database\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Gemueseeggli\Database\Model\BaseModel;
use Gemueseeggli\Database\Model\Article;
use \Datetime;

require_once('BaseModel.php');
/**
 * @Entity @Table(name="articletypes")
 */
class Articletype extends BaseModel
{
    /**
     * @Column(type="decimal", precision=6, scale=2)
     * @var integer
     **/
    public $price;
    /**
     * @Column(type="string", nullable=true)
     * @var string
     **/
    public $description;
    /**
     * @ManyToOne(targetEntity="Article", inversedBy="assignedArticletypes", fetch="EAGER")
     * @JoinColumn(name="article_id", referencedColumnName="id", nullable=false)
     */
    public $article;
    /**
     * @OneToMany(targetEntity="Abo", mappedBy="articletype")
     */
    protected $assignedAbos;
    /**
     * @Column(type="boolean")
     * @var boolean
     **/
    public $onlyAdmin;
    /**
     * @Column(type="boolean")
     * @var boolean
     **/
     public $isActive;

    public function __construct()
    {
        $this->assignedAbos = new ArrayCollection();
    }
}
