<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ElevesRepository")
 */
class Eleves
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $numero_equipe;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $lettre_national;

    /**
     * @ORM\Column(type="string", length=63)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $prenom;

    public function getId()
    {
        return $this->id;
    }

    public function getNumeroEquipe(): ?string
    {
        return $this->numero_equipe;
    }

    public function setNumeroEquipe(string $numero_equipe): self
    {
        $this->numero_equipe = $numero_equipe;

        return $this;
    }

    public function getLettreNational(): ?string
    {
        return $this->lettre_national;
    }

    public function setLettreNational(?string $lettre_national): self
    {
        $this->lettre_national = $lettre_national;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }
}
