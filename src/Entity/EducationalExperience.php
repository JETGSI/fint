<?php

namespace App\Entity;

use App\Repository\EducationalExperienceRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: EducationalExperienceRepository::class)]
class EducationalExperience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $university = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'educationalExperiences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CurriculumVitae $curriculumvitae = null;

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

    public function getUniversity(): ?string
    {
        return $this->university;
    }

    public function setUniversity(string $university): self
    {
        $this->university = $university;

        return $this;
    }

    public function getPeriod(): array
    {
        return $this->period;
    }

    public function setPeriod(array $period): self
    {
        $this->period = $period;

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

    public function getCurriclumVitae(): ?CurriculumVitae
    {
        return $this->curriculumvitae;
    }

    public function setCurriclumVitae(?CurriculumVitae $curriclumVitae): self
    {
        $this->curriculumvitae = $curriclumVitae;

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
