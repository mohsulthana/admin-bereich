<?php
namespace Gemueseeggli\Database\Model;

// Database/Model/BaseModel.php
abstract class BaseModel
{
    /**
     * @Id @Column(type="integer") @GeneratedValue
     **/
    public $id;

    public function getId()
    {
        return $this->id;
    }

    protected function getProperties()
    {
        return get_object_vars($this);
    }
}
