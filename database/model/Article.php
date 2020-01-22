<?php

namespace Gemueseeggli\Database\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Gemueseeggli\Database\Model\BaseModel;
use Gemueseeggli\Database\Model\Price;

require_once('BaseModel.php');
/**
 * @Entity @Table(name="articles")
 */
class Article extends BaseModel
{
    /**
     * @Column(type="string", nullable=true)
     * @var string
     **/
    public $description;
    /**
     * @Column(type="string", nullable=true)
     * @var string
     **/
    public $name;
    /**
     * @Column(type="string", nullable=true)
     * @var string
     **/
    public $imagepath;
    /**
     * @Column(type="boolean")
     * @var boolean
     **/
    public $hasOrigin;
    /**
     * @OneToMany(targetEntity="Articletype", mappedBy="article")
     */
    public $assignedArticletypes;

    public function __construct()
    {
        $this->assignedArticletypes = new ArrayCollection();
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    public function getDescription()
    {
        return $this->description;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getName()
    {
        return $this->name;
    }

    public function setImagepath($imagepath)
    {
        $this->imagepath = $imagepath;
    }
    
    public function getImagepath()
    {
        return $this->imagepath;
    }



    public function getLowestPrice()
    {
        $articletypes = $this->assignedArticletypes->getValues();
        if (count($articletypes) <= 0) {
            return 0;
        }
        if (count($articletypes) > 1) {
            $filteredArray = array();
            foreach ($articletypes as $articletype){
                if ($articletype->onlyAdmin == false){
                    $filteredArray[] = $articletype;
                }
            }

            $articletypes = $filteredArray;

            usort($articletypes, function ($a, $b) {
                return $a->price == $b->price ? 0 : ( $a->price > $b->price ) ? 1 : -1;
            });
        }

        return reset($articletypes)->price;
    }

    public function containsArticletype($articletype)
    {
        if (count($this->assignedArticletypes) == 0) {
            return false;
        }
        foreach ($this->assignedArticletypes as $key => $actArticletype) {
            if ($actArticletype->id == $articletype->id) {
                return true;
            }
        }
        return false;
    }

    public function getArticleImagePath()
    {
        $directory = '\img\articles';
        return $directory.'\\'.$this->imagepath;
    }
}
