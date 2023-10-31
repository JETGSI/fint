<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\StudentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student 
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne( cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;


    #[ORM\OneToMany(mappedBy: 'student', targetEntity: JobInterview::class)]
    private Collection $jobInterviews;


    #[ORM\Column(length: 255)]
    private ?string $img = null;

    #[ORM\Column]
    private ?bool $je = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $linkedinLink = null;

    #[ORM\OneToMany(mappedBy: 'Student', targetEntity: JobRequest::class)]
    private Collection $jobRequests;

    #[ORM\Column(length: 18)]
    private ?string $telephone = null;

    #[ORM\Column]
    private ?bool $sharedata = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $linkCv = null;

    
    public function __construct()
    {
        $this->jobRequests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

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
            $jobInterview->setUser($this->user);
        }

        return $this;
    }

    public function removeJobInterview(JobInterview $jobInterview): self
    {
        if ($this->jobInterviews->removeElement($jobInterview)) {
            // set the owning side to null (unless already changed)
            if ($jobInterview->getUser() === $this) {
                $jobInterview->setUser(null);
            }
        }

        return $this;
    }


    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function isJe(): ?bool
    {
        return $this->je;
    }

    public function setJe(bool $je): self
    {
        $this->je = $je;

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

    public function getLinkedinLink(): ?string
    {
        return $this->linkedinLink;
    }

    public function setLinkedinLink(?string $linkedinLink): self
    {
        $this->linkedinLink = $linkedinLink;

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
            $jobRequest->setStudent($this);
        }

        return $this;
    }

    public function removeJobRequest(JobRequest $jobRequest): self
    {
        if ($this->jobRequests->removeElement($jobRequest)) {
            // set the owning side to null (unless already changed)
            if ($jobRequest->getStudent() === $this) {
                $jobRequest->setStudent(null);
            }
        }

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

    public function isSharedata(): ?bool
    {
        return $this->sharedata;
    }

    public function setSharedata(bool $sharedata): self
    {
        $this->sharedata = $sharedata;

        return $this;
    }
    public function getLinkCv(): ?string
    {
        return $this->linkCv;
    }

    public function setLinkCv(?string $linkCv): self
    {
        $this->linkCv = $linkCv;

        return $this;
    }
}
