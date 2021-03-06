<?php

namespace App\Entity;

use App\Repository\EvaluationRepository;
use App\Traits\CompanyEntityTrait;
use App\Traits\TimestampableTrait;
use App\Traits\UuidEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EvaluationRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Evaluation
{
    use UuidEntityTrait;
    use TimestampableTrait;
    use CompanyEntityTrait;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $objective;

    /**
     * @ORM\Column(type="integer")
     */
    private $attempts;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     */
    private $requirements;

    /**
     * @ORM\Column(type="float")
     */
    private $percentage;

    /**
     * @ORM\Column(type="integer")
     */
    private $time;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="evaluation",cascade={"persist","remove"})
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity=Answer::class, mappedBy="evaluation")
     */
    private $answers;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $points;

    /**
     * @ORM\Column(type="boolean")
     */
    private $certificate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $accumulated = 0;

    /**
     * @ORM\OneToOne(targetEntity=Unit::class, cascade={"persist"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $unit;

    /**
     * @ORM\OneToOne(targetEntity=Certificate::class, mappedBy="evaluation", cascade={"persist", "remove"})
     */
    private $certificateObj;

  
    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getObjective(): ?string
    {
        return $this->objective;
    }

    public function setObjective(string $objective): self
    {
        $this->objective = $objective;

        return $this;
    }

    public function getAttempts(): ?int
    {
        return $this->attempts;
    }

    public function setAttempts(int $attempts): self
    {
        $this->attempts = $attempts;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRequirements(): ?bool
    {
        return $this->requirements;
    }

    public function setRequirements(bool $requirements): self
    {
        $this->requirements = $requirements;

        return $this;
    }

    public function getPercentage(): ?float
    {
        return $this->percentage;
    }

    public function setPercentage(float $percentage): self
    {
        $this->percentage = $percentage;

        return $this;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(int $time): self
    {
        $this->time = $time;

        return $this;
    }


    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setEvaluation($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getEvaluation() === $this) {
                $question->setEvaluation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setEvaluation($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getEvaluation() === $this) {
                $answer->setEvaluation(null);
            }
        }

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): self
    {
        $this->points = $points;

        return $this;
    }

    public function getCertificate(): ?bool
    {
        return $this->certificate;
    }

    public function setCertificate(bool $certificate): self
    {
        $this->certificate = $certificate;

        return $this;
    }

    public function getAccumulated(): ?int
    {
        return $this->accumulated ? $this->accumulated : 0;
    }

    public function setAccumulated(?int $accumulated): self
    {
        $this->accumulated = $accumulated;

        return $this;
    }

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function setUnit(?Unit $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getCertificateObj(): ?Certificate
    {
        return $this->certificateObj;
    }

    public function setCertificateObj(Certificate $certificateObj): self
    {
        // set the owning side of the relation if necessary
        if ($certificateObj->getEvaluation() !== $this) {
            $certificateObj->setEvaluation($this);
        }

        $this->certificateObj = $certificateObj;

        return $this;
    }


}
