<?php
namespace Gemueseeggli\Database\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Gemueseeggli\Database\Model\User;

require_once('BaseModel.php');
/**
 * @Entity @Table(name="pauses")
 */
class Pause extends BaseModel
{
    /**
     * @Column(type="datetime")
     * @var datetime
     **/
    public $startdate;
    /**
     * @ManyToOne(targetEntity="User", inversedBy="pauses")
     * @JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    public $user;
    /**
     * @Column(type="datetime")
     * @var datetime
     **/
    public $enddate;
}