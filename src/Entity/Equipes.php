<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EquipesRepository")
 */
class Equipes
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
    private $lettre_national;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $numero_equipe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    public function getId()
    {
        return $this->id;
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

    public function getNumeroEquipe(): ?string
    {
        return $this->numero_equipe;
    }

    public function setNumeroEquipe(string $numero_equipe): self
    {
        $this->numero_equipe = $numero_equipe;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }
}
