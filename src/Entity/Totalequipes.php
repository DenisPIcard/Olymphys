<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Totalequipes
 *
 * @ORM\Table(name="totalequipes")
 * @ORM\Entity(repositoryClass="App\Repository\TotalequipesRepository")
 */
class Totalequipes
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
     * @var int
     *
     * @ORM\Column(name="numero_equipe", type="smallint", nullable=true)
     */
    private $numeroEquipe;

    /**
     * @var string
     *
     * @ORM\Column(name="lettre_equipe", type="string", length=1, nullable=true)
     */
    private $lettreEquipe;
    /**
     * @var string
     *
     * @ORM\Column(name="nom_equipe", type="string", length=255, nullable=true)
     */
    private $nomEquipe;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_lycee", type="string", length=255, nullable=true)
     */
    private $nomLycee;

    /**
     * @var string
     *
     * @ORM\Column(name="denomination_lycee", type="string", length=255, nullable=true)
     */
    private $denominationLycee;

    /**
     * @var string
     *
     * @ORM\Column(name="lycee_localite", type="string", length=255, nullable=true)
     */
    private $lyceeLocalite;

    /**
     * @var string
     *
     * @ORM\Column(name="lycee_academie", type="string", length=255, nullable=true)
     */
    private $lyceeAcademie;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom_prof1", type="string", length=255, nullable=true)
     */
    private $prenomProf1;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_prof1", type="string", length=255, nullable=true)
     */
    private $nomProf1;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom_prof2", type="string", length=255, nullable=true)
     */
    private $prenomProf2;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_prof2", type="string", length=255, nullable=true)
     */
    private $nomProf2;




    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set numeroEquipe
     *
     * @param integer $numeroEquipe
     *
     * @return Totalequipes
     */
    public function setNumeroEquipe($numeroEquipe)
    {
        $this->numeroEquipe = $numeroEquipe;

        return $this;
    }

    /**
     * Get numeroEquipe
     *
     * @return integer
     */
    public function getNumeroEquipe()
    {
        return $this->numeroEquipe;
    }

    /**
     * Set lettreEquipe
     *
     * @param string $lettreEquipe
     *
     * @return Totalequipes
     */
    public function setLettreEquipe($lettreEquipe)
    {
        $this->lettreEquipe = $lettreEquipe;

        return $this;
    }

    /**
     * Get lettreEquipe
     *
     * @return string
     */
    public function getLettreEquipe()
    {
        return $this->lettreEquipe;
    }

    /**
     * Set nomEquipe
     *
     * @param string $nomEquipe
     *
     * @return Totalequipes
     */
    public function setNomEquipe($nomEquipe)
    {
        $this->nomEquipe = $nomEquipe;

        return $this;
    }

    /**
     * Get nomEquipe
     *
     * @return string
     */
    public function getNomEquipe()
    {
        return $this->nomEquipe;
    }

    /**
     * Set nomLycee
     *
     * @param string $nomLycee
     *
     * @return Totalequipes
     */
    public function setNomLycee($nomLycee)
    {
        $this->nomLycee = $nomLycee;

        return $this;
    }

    /**
     * Get nomLycee
     *
     * @return string
     */
    public function getNomLycee()
    {
        return $this->nomLycee;
    }

    /**
     * Set denominationLycee
     *
     * @param string $denominationLycee
     *
     * @return Totalequipes
     */
    public function setDenominationLycee($denominationLycee)
    {
        $this->denominationLycee = $denominationLycee;

        return $this;
    }

    /**
     * Get denominationLycee
     *
     * @return string
     */
    public function getDenominationLycee()
    {
        return $this->denominationLycee;
    }

    /**
     * Set lyceeLocalite
     *
     * @param string $lyceeLocalite
     *
     * @return Totalequipes
     */
    public function setLyceeLocalite($lyceeLocalite)
    {
        $this->lyceeLocalite = $lyceeLocalite;

        return $this;
    }

    /**
     * Get lyceeLocalite
     *
     * @return string
     */
    public function getLyceeLocalite()
    {
        return $this->lyceeLocalite;
    }

    /**
     * Set lyceeAcademie
     *
     * @param string $lyceeAcademie
     *
     * @return Totalequipes
     */
    public function setLyceeAcademie($lyceeAcademie)
    {
        $this->lyceeAcademie = $lyceeAcademie;

        return $this;
    }

    /**
     * Get lyceeAcademie
     *
     * @return string
     */
    public function getLyceeAcademie()
    {
        return $this->lyceeAcademie;
    }

    /**
     * Set prenomProf1
     *
     * @param string $prenomProf1
     *
     * @return Totalequipes
     */
    public function setPrenomProf1($prenomProf1)
    {
        $this->prenomProf1 = $prenomProf1;

        return $this;
    }

    /**
     * Get prenomProf1
     *
     * @return string
     */
    public function getPrenomProf1()
    {
        return $this->prenomProf1;
    }

    /**
     * Set nomProf1
     *
     * @param string $nomProf1
     *
     * @return Totalequipes
     */
    public function setNomProf1($nomProf1)
    {
        $this->nomProf1 = $nomProf1;

        return $this;
    }

    /**
     * Get nomProf1
     *
     * @return string
     */
    public function getNomProf1()
    {
        return $this->nomProf1;
    }

    /**
     * Set prenomProf2
     *
     * @param string $prenomProf2
     *
     * @return Totalequipes
     */
    public function setPrenomProf2($prenomProf2)
    {
        $this->prenomProf2 = $prenomProf2;

        return $this;
    }

    /**
     * Get prenomProf2
     *
     * @return string
     */
    public function getPrenomProf2()
    {
        return $this->prenomProf2;
    }

    /**
     * Set nomProf2
     *
     * @param string $nomProf2
     *
     * @return Totalequipes
     */
    public function setNomProf2($nomProf2)
    {
        $this->nomProf2 = $nomProf2;

        return $this;
    }

    /**
     * Get nomProf2
     *
     * @return string
     */
    public function getNomProf2()
    {
        return $this->nomProf2;
    }
}
