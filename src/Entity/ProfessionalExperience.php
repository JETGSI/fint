<?php

namespace App\Entity;

use App\Repository\ProfessionalExperienceRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ProfessionalExperienceRepository::class)]
class ProfessionalExperience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'professionalExperiences')]
    private ?CurriculumVitae $curriculumvitae = null;

    #[ORM\Column(length: 255)]
    private ?string $Entreprise = null;

    #[ORM\Column(length: 255)]
    private ?string $poste = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $startdate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThan(
        propertyPath:"startdate",
        message:"Date de fin ne peut pas être inférieur à la date de début"
    )]
    private ?\DateTimeInterface $enddate = null;


    #[ORM\Column(type: "datetime")]
    private $createdAt;

    #[ORM\Column(type: "datetime")]
    private $updatedAt;

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();

    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new DateTime();
    }
    
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }
    


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }


    public function getCurriculumVitae(): ?CurriculumVitae
    {
        return $this->curriculumvitae;
    }

    public function setCurriculumVitae(?CurriculumVitae $curriculumVitae): self
    {
        $this->curriculumvitae = $curriculumVitae;

        return $this;
    }

    public function getEntreprise(): ?string
    {
        return $this->Entreprise;
    }

    public function setEntreprise(string $Entreprise): self
    {
        $this->Entreprise = $Entreprise;

        return $this;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): self
    {
        $this->poste = $poste;

        return $this;
    }

    public function getStartdate(): ?\DateTimeInterface
    {
        return $this->startdate;
    }

    public function setStartdate(\DateTimeInterface $startdate): self
    {
        $this->startdate = $startdate;

        return $this;
    }

    public function getEnddate(): ?\DateTimeInterface
    {
        return $this->enddate;
    }

    public function setEnddate(\DateTimeInterface $enddate): self
    {
        $this->enddate = $enddate;

        return $this;
    }

    public function getcreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setcreatedAt(DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getupdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setupdatedAt(DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


}
