<?php

namespace App\Application\Sonata\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseUser as BaseUser;

/**
 * This file has been generated by the SonataEasyExtendsBundle.
 *
 * @link https://sonata-project.org/easy-extends
 *
 * References:
 * @link http://www.doctrine-project.org/projects/orm/2.0/docs/reference/working-with-objects/en
 */

/**
 * User
 *
 * @ORM\Table(name="fos_user_user")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser
{
    /**
     * @var int $id
     */
    protected $id;
    /**
     * @var string
     *
     * @ORM\Column(name="rne", type="string", length=255, nullable=true)
     */
    protected $rne;

    /**
     * Set rne
     *
     * @param string $nom
     *
     * @return User
     */
    public function setRne( $rne) {
        $this->rne= $rne;

        return $this;
    }

    /**
     * Get rne
     *
     * @return string
     */
    public function getRne() {
        return $this->rne;
    }


    /**
     * Get id.
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }
}
