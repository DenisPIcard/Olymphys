<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Equipes
 *
 * @ORM\Table(name="equipes")
 * @ORM\Entity(repositoryClass="App\Repository\EquipesRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Equipes
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="lettre", type="string", length=255, unique=true)
     */
    private $lettre;

    /**
     * @var string
     *
     * @ORM\Column(name="titreProjet", type="string", length=255, unique=true)
     */
    private $titreProjet;

    /**
     * @var string
     *
     * @ORM\Column(name="titreCourt", type="string", length=255, unique=true)
     */
    private $titreCourt;

    /**
     * @var string
     *
     * @ORM\Column(name="urlMemoire", type="string", length=255, nullable=true, unique=true)
     */
    private $urlMemoire;

    /**
     * @var int
     *
     * @ORM\Column(name="total", type="smallint", nullable=true)
     */
    private $total;

    /**
     * @var string
     * @ORM\Column(name="classement", type="string", length=255, nullable=true)
     */
    private $classement;

    /**
     * @var int
     * @ORM\Column(name="rang", type="smallint", nullable=true)
     */
    private $rang;

 


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lettre
     *
     * @param string $lettre
     *
     * @return Equipes
     */
    public function setLettre($lettre)
    {
        $this->lettre = $lettre;

        return $this;
    }

    /**
     * Get lettre
     *
     * @return string
     */
    public function getLettre()
    {
        return $this->lettre;
    }

    /**
     * Set titreProjet
     *
     * @param string $titreProjet
     *
     * @return Equipes
     */
    public function setTitreProjet($titreProjet)
    {
        $this->titreProjet = $titreProjet;

        return $this;
    }

    /**
     * Get titreProjet
     *
     * @return string
     */
    public function getTitreProjet()
    {
        return $this->titreProjet;
    }

    /**
     * Set titreCourt
     *
     * @param string $titreCourt
     *
     * @return Equipes
     */
    public function setTitreCourt($titreCourt)
    {
        $this->titreCourt = $titreCourt;

        return $this;
    }

    /**
     * Get titreCourt
     *
     * @return string
     */
    public function getTitreCourt()
    {
         return $this->titreCourt;
    }

    /**
     * Set urlMemoire
     *
     * @param string $urlMemoire
     *
     * @return Equipes
     */
    public function setUrlMemoire($urlMemoire)
    {
        $this->urlMemoire = $urlMemoire;

        return $this;
    }

    /**
     * Get urlMemoire
     *
     * @return string
     */
    public function getUrlMemoire()
    {
        return $this->urlMemoire;
    }

   
}
