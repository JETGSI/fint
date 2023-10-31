<?php

namespace App\Entity;

use App\Repository\AssociativeExperienceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;


#[ORM\Entity(repositoryClass: AssociativeExperienceRepository::class)]
class AssociativeExperience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $organization = null;

    #[ORM\ManyToOne(inversedBy: 'associativeExperiences')]
    private ?CurriculumVitae $curriculumvitae = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $startdate = null;


    #[Assert\GreaterThan(
        propertyPath:"startdate",
        message:"Date de fin ne peut pas être inférieur à la date de début"
    )]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }


    public function getOrganization(): ?string
    {
        return $this->organization;
    }

    public function setOrganization(string $organization): self
    {
        $this->organization = $organization;

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
