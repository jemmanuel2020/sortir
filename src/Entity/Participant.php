<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
abstract class Participant implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $idParticipant;

    /**
     * @Assert\Email(message="Your email is not valid!")
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $mail;

    /**
     * @ORM\Column(type="boolean")
     */
    private $administrateur;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $motPasse;

    /**
     * @Assert\NotBlank(message="Please provide your username!")
     * @Assert\Length(
     *     min=3,
     *     max=50,
     *     minMessage="Minimum 3 characters please!",
     *     maxMessage="Maximum 50 characters please!"
     * )
     * @Assert\Regex(pattern="/^[a-z0-9_-]+$/i", message="Please use only letters, numbers, underscores and dashes!")
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $telephone;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @ORM\JoinColumn(referencedColumnName="id_sortie")
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="organisateur")
     */
    private $sortiesOrganisees;

    /**
     * @ORM\JoinColumn(referencedColumnName="id_sortie")
     * @ORM\ManyToMany(targetEntity=Sortie::class, mappedBy="participants")
     */
    private $sorties;

    /**
     * @ORM\JoinColumn(referencedColumnName="id_campus")
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="users")
     */
    private $campus;

    public function __construct()
    {
        $this->sortiesOrganisees = new ArrayCollection();
        $this->sorties = new ArrayCollection();
    }

    public function getIdParticipant(): ?int
    {
        return $this->idParticipant;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setEmail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->pseudo;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getMotPasse(): string
    {
        return (string) $this->motPasse;
    }

    public function setMotPasse(string $motPasse): self
    {
        $this->motPasse = $motPasse;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function setUsername(string $pseudo): self
    {
        $this->pseudo = $pseudo;

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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSortiesOrganisees(): Collection
    {
        return $this->sortiesOrganisees;
    }

    public function addSortiesOrganisees(Sortie $sortiesOrganisees): self
    {
        if (!$this->sortiesOrganisees->contains($sortiesOrganisees)) {
            $this->sortiesOrganisees[] = $sortiesOrganisees;
            $sortiesOrganisees->setOrganisateur($this);
        }

        return $this;
    }

    public function removeSortiesOrganisees(Sortie $sortiesOrganisees): self
    {
        if ($this->sortiesOrganisees->removeElement($sortiesOrganisees)) {
            // set the owning side to null (unless already changed)
            if ($sortiesOrganisees->getOrganisateur() === $this) {
                $sortiesOrganisees->setOrganisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSortie(Sortie $sortie): self
    {
        if (!$this->sorties->contains($sortie)) {
            $this->sorties[] = $sortie;
            $sortie->addParticipant($this);
        }

        return $this;
    }

    public function removeSortie(Sortie $sortie): self
    {
        if ($this->sorties->removeElement($sortie)) {
            $sortie->removeParticipant($this);
        }

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }


}
