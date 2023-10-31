<?php

namespace App\Entity;

use App\Repository\EntrepriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


/**
 * @method string getUserIdentifier()
 */
#[ORM\Entity(repositoryClass: EntrepriseRepository::class)]
class Entreprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $adress = null;

    #[ORM\Column(type: Types::JSON)]
    private array $service = [];

    #[ORM\Column(length: 255)]
    private ?string $logoPath = null;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: JobRequest::class)]
    private Collection $jobRequests;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: JobOffer::class)]
    private Collection $jobOffers;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: JobInterview::class)]
    private Collection $jobInterviews;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    public function __construct()
    {
        $this->jobRequests = new ArrayCollection();
        $this->jobOffers = new ArrayCollection();
        $this->jobInterviews = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getService(): array
    {
        return $this->service;
    }

    public function setService(array $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getLogoPath(): ?string
    {
        return $this->logoPath;
    }

    public function setLogoPath(string $logoPath): self
    {
        $this->logoPath = $logoPath;

        return $this;
    }

    /**
     * @return Collection<int, JobRequest>
     */
    public function getJobRequests(): Collection
    {
        return $this->jobRequests;
    }

    public function addJobRequest(JobRequest $jobRequest): self
    {
        if (!$this->jobRequests->contains($jobRequest)) {
            $this->jobRequests->add($jobRequest);
            $jobRequest->setEntreprise($this);
        }

        return $this;
    }

    public function removeJobRequest(JobRequest $jobRequest): self
    {
        if ($this->jobRequests->removeElement($jobRequest)) {
            // set the owning side to null (unless already changed)
            if ($jobRequest->getEntreprise() === $this) {
                $jobRequest->setEntreprise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, JobOffer>
     */
    public function getJobOffers(): Collection
    {
        return $this->jobOffers;
    }

    public function addJobOffer(JobOffer $jobOffer): self
    {
        if (!$this->jobOffers->contains($jobOffer)) {
            $this->jobOffers->add($jobOffer);
            $jobOffer->setEntreprise($this);
        }

        return $this;
    }

    public function removeJobOffer(JobOffer $jobOffer): self
    {
        if ($this->jobOffers->removeElement($jobOffer)) {
            // set the owning side to null (unless already changed)
            if ($jobOffer->getEntreprise() === $this) {
                $jobOffer->setEntreprise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, JobInterview>
     */
    public function getJobInterviews(): Collection
    {
        return $this->jobInterviews;
    }

    public function addJobInterview(JobInterview $jobInterview): self
    {
        if (!$this->jobInterviews->contains($jobInterview)) {
            $this->jobInterviews->add($jobInterview);
            $jobInterview->setEntreprise($this);
        }

        return $this;
    }

    public function removeJobInterview(JobInterview $jobInterview): self
    {
        if ($this->jobInterviews->removeElement($jobInterview)) {
            // set the owning side to null (unless already changed)
            if ($jobInterview->getEntreprise() === $this) {
                $jobInterview->setEntreprise(null);
            }
        }

        return $this;
    }
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

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
}
