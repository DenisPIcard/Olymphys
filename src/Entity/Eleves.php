<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Eleves
 *
 * @ORM\Table(name="eleves")
 * @ORM\Entity(repositoryClass="App\Repository\ElevesRepository")
 */
class Eleves
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
    * @ORM\ManyToOne(targetEntity="App\Entity\Equipes", inversedBy="eleves")
    * @ORM\JoinColumn(nullable=true)
    */
    private $equipeleves;

    /**
     * @var int
     *
     * @ORM\Column(name="id_eleve", type="integer", nullable=true)
     */
    private $idEleve;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;
    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="classe", type="string", length=255, nullable=true)
     */
    private $classe;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=1, nullable=true)
     */
    private $sexe;

    /**
     * @var string
     *
     * @ORM\Column(name="numero_adresse", type="string", length=255, nullable=true)
     */
    private $numeroAdresse;

    /**
     * @var string
     *
     * @ORM\Column(name="nature_voie", type="string", length=255, nullable=true)
     */
    private $natureVoie;

    /**
     * @var string
     *
     * @ORM\Column(name="rue", type="string", length=255, nullable=true)
     */
    private $rue;

    /**
     * @var string
     *
     * @ORM\Column(name="code_postal", type="string", length=255, nullable=true)
     */
    private $codePostal;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255, nullable=true)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="complement_adresse", type="string", length=255, nullable=true)
     */
    private $complementAdresse;

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
     * @ORM\Column(name="localite_lycee", type="string", length=255, nullable=true)
     */
    private $localiteLycee;

    /**
     * @var string
     *
     * @ORM\Column(name="rne_lycee", type="string", length=8, nullable=true)
     */
    private $rneLycee;

    /**
     * @var int
     *
     * @ORM\Column(name="id_equipe", type="smallint", nullable=true)
     */
    private $idEquipe;

    /**
     * @var int
     *
     * @ORM\Column(name="numero_equipe", type="smallint", nullable=true)
     */
    private $numeroEquipe;

    /**
    */
    /**
     * @var string
     *
     * @ORM\Column(name="lettre_equipe", type="string",length=1, nullable=true)
     */
    private $lettreEquipe;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nom_equipe", type="string", length=255, nullable=true)
     */
    private $nomEquipe;



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
     * Set idEleve
     *
     * @param integer $idEleve
     *
     * @return Eleves
     */
    public function setIdEleve($idEleve)
    {
        $this->idEleve = $idEleve;

        return $this;
    }

    /**
     * Get idEleve
     *
     * @return integer
     */
    public function getIdEleve()
    {
        return $this->idEleve;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Eleves
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Eleves
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set classe
     *
     * @param string $classe
     *
     * @return Eleves
     */
    public function setClasse($classe)
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * Get classe
     *
     * @return string
     */
    public function getClasse()
    {
        return $this->classe;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Eleves
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set sexe
     *
     * @param string $sexe
     *
     * @return Eleves
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe
     *
     * @return string
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * Set numeroAdresse
     *
     * @param string $numeroAdresse
     *
     * @return Eleves
     */
    public function setNumeroAdresse($numeroAdresse)
    {
        $this->numeroAdresse = $numeroAdresse;

        return $this;
    }

    /**
     * Get numeroAdresse
     *
     * @return string
     */
    public function getNumeroAdresse()
    {
        return $this->numeroAdresse;
    }

    /**
     * Set natureVoie
     *
     * @param string $natureVoie
     *
     * @return Eleves
     */
    public function setNatureVoie($natureVoie)
    {
        $this->natureVoie = $natureVoie;

        return $this;
    }

    /**
     * Get natureVoie
     *
     * @return string
     */
    public function getNatureVoie()
    {
        return $this->natureVoie;
    }

    /**
     * Set rue
     *
     * @param string $rue
     *
     * @return Eleves
     */
    public function setRue($rue)
    {
        $this->rue = $rue;

        return $this;
    }

    /**
     * Get rue
     *
     * @return string
     */
    public function getRue()
    {
        return $this->rue;
    }

    /**
     * Set codePostal
     *
     * @param integer $codePostal
     *
     * @return Eleves
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return integer
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return Eleves
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set complementAdresse
     *
     * @param string $complementAdresse
     *
     * @return Eleves
     */
    public function setComplementAdresse($complementAdresse)
    {
        $this->complementAdresse = $complementAdresse;

        return $this;
    }

    /**
     * Get complementAdresse
     *
     * @return string
     */
    public function getComplementAdresse()
    {
        return $this->complementAdresse;
    }

    /**
     * Set nomLycee
     *
     * @param string $nomLycee
     *
     * @return Eleves
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
     * @return Eleves
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
     * Set localiteLycee
     *
     * @param string $localiteLycee
     *
     * @return Eleves
     */
    public function setLocaliteLycee($localiteLycee)
    {
        $this->localiteLycee = $localiteLycee;

        return $this;
    }

    /**
     * Get localiteLycee
     *
     * @return string
     */
    public function getLocaliteLycee()
    {
        return $this->localiteLycee;
    }

    /**
     * Set rneLycee
     *
     * @param string $rneLycee
     *
     * @return Eleves
     */
    public function setRneLycee($rneLycee)
    {
        $this->rneLycee = $rneLycee;

        return $this;
    }

    /**
     * Get rneLycee
     *
     * @return string
     */
    public function getRneLycee()
    {
        return $this->rneLycee;
    }

    /**
     * Set idEquipe
     *
     * @param integer $idEquipe
     *
     * @return Eleves
     */
    public function setIdEquipe($idEquipe)
    {
        $this->idEquipe = $idEquipe;

        return $this;
    }

    /**
     * Get idEquipe
     *
     * @return integer
     */
    public function getIdEquipe()
    {
        return $this->idEquipe;
    }

    /**
     * Set numeroEquipe
     *
     * @param integer $numeroEquipe
     *
     * @return Eleves
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
     * @return Eleves
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
     * @return Eleves
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
     * Set equipeleves
     *
     * @param \App\Entity\Equipes $equipeleves
     *
     * @return Eleves
     */
    public function setEquipeleves(\App\Entity\Equipes $equipeleves = null)
    {
        $this->equipeleves = $equipeleves;

        return $this;
    }

    /**
     * Get equipeleves
     *
     * @return \App\Entity\Equipes
     */
    public function getEquipeleves()
    {
        return $this->equipeleves;
    }
}
