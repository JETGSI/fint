<?php

namespace App\Entity;

use App\Repository\JobRequestRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobRequestRepository::class)]
class JobRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'jobRequests')]
    private ?Entreprise $entreprise = null;

    #[ORM\Column(length: 100)]
    private ?string $refsujet = null;

    #[ORM\ManyToOne(inversedBy: 'jobRequests')]
    private ?Student $Student = null;



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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }


    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getRefsujet(): ?string
    {
        return $this->refsujet;
    }

    public function setRefsujet(string $refsujet): self
    {
        $this->refsujet = $refsujet;

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->Student;
    }

    public function setStudent(?Student $Student): self
    {
        $this->Student = $Student;

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
