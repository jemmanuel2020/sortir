<?php

namespace App\Modele;

use App\Repository\ModeleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ModeleRepository::class)
 */
class Modele
{
    /*/**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")

    private $id;*/

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomCampus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomSortie;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateSortie1;

    /**
     * @Assert\GreaterThan(propertyPath="dateSortie1")
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateSortie2;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $organisateur;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $inscrit;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $pasInscrit;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $sortiePassees;

    /*public function getId(): ?int
    {
        return $this->id;
    }*/

    public function getNomCampus(): ?string
    {
        return $this->nomCampus;
    }

    public function setNomCampus(?string $nomCampus): self
    {
        $this->nomCampus = $nomCampus;

        return $this;
    }

    public function getNomSortie(): ?string
    {
        return $this->nomSortie;
    }

    public function setNomSortie(?string $nomSortie): self
    {
        $this->nomSortie = $nomSortie;

        return $this;
    }

    public function getDateSortie1(): ?\DateTimeInterface
    {
        return $this->dateSortie1;
    }

    public function setDateSortie1(?\DateTimeInterface $dateSortie1): self
    {
        $this->dateSortie1 = $dateSortie1;

        return $this;
    }

    public function getDateSortie2(): ?\DateTimeInterface
    {
        return $this->dateSortie2;
    }

    public function setDateSortie2(?\DateTimeInterface $dateSorite2): self
    {
        $this->dateSortie2 = $dateSorite2;

        return $this;
    }

    public function getOrganisateur(): ?bool
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?bool $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    public function getInscrit(): ?bool
    {
        return $this->inscrit;
    }

    public function setInscrit(?bool $inscrit): self
    {
        $this->inscrit = $inscrit;

        return $this;
    }

    public function getPasInscrit(): ?bool
    {
        return $this->pasInscrit;
    }

    public function setPasInscrit(?bool $pasInscrit): self
    {
        $this->pasInscrit = $pasInscrit;

        return $this;
    }

    public function getSortiePassees(): ?bool
    {
        return $this->sortiePassees;
    }

    public function setSortiePassees(?bool $sortiePassees): self
    {
        $this->sortiePassees = $sortiePassees;

        return $this;
    }
}
