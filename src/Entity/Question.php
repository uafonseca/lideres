<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Question
{
    const QUESTION_TYPE_TRUE_OR_FALSE = 'Verdadero o Falso';
    const QUESTION_TYPE_OPEN_TEXT = 'Texto Abierto';
    const QUESTION_TYPE_MULTI_SELECTCTION = 'Selección Multiple';
    const QUESTION_TYPE_UNIQ_SELECT_SELECTION = 'Selección Única';
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
     * @ORM\ManyToOne(targetEntity=Evaluation::class, inversedBy="questions")
     */
    private $evaluation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Choice::class, mappedBy="question",cascade={"persist","remove"})
     */
    private $choices;

    /**
     * @ORM\OneToMany(targetEntity=SingleQuestion::class, mappedBy="question",cascade={"persist","remove"})
     */
    private $singleQuestions;

    /**
     * @ORM\OneToMany(targetEntity=AnswerQuestion::class, mappedBy="question")
     */
    private $answerQuestions;

    public function __construct()
    {
        $this->choices = new ArrayCollection();
        $this->singleQuestions = new ArrayCollection();
        $this->answerQuestions = new ArrayCollection();
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

    public function getEvaluation(): ?Evaluation
    {
        return $this->evaluation;
    }

    public function setEvaluation(?Evaluation $evaluation): self
    {
        $this->evaluation = $evaluation;

        return $this;
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

    /**
     * @return Collection|Choice[]
     */
    public function getChoices(): Collection
    {
        return $this->choices;
    }

    public function addChoice(Choice $choice): self
    {
        if (!$this->choices->contains($choice)) {
            $this->choices[] = $choice;
            $choice->setQuestion($this);
        }

        return $this;
    }

    public function removeChoice(Choice $choice): self
    {
        if ($this->choices->removeElement($choice)) {
            // set the owning side to null (unless already changed)
            if ($choice->getQuestion() === $this) {
                $choice->setQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SingleQuestion[]
     */
    public function getSingleQuestions(): Collection
    {
        return $this->singleQuestions;
    }

    public function addSingleQuestion(SingleQuestion $singleQuestion): self
    {
        if (!$this->singleQuestions->contains($singleQuestion)) {
            $this->singleQuestions[] = $singleQuestion;
            $singleQuestion->setQuestion($this);
        }

        return $this;
    }

    public function removeSingleQuestion(SingleQuestion $singleQuestion): self
    {
        if ($this->singleQuestions->removeElement($singleQuestion)) {
            // set the owning side to null (unless already changed)
            if ($singleQuestion->getQuestion() === $this) {
                $singleQuestion->setQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AnswerQuestion[]
     */
    public function getAnswerQuestions(): Collection
    {
        return $this->answerQuestions;
    }

    public function addAnswerQuestion(AnswerQuestion $answerQuestion): self
    {
        if (!$this->answerQuestions->contains($answerQuestion)) {
            $this->answerQuestions[] = $answerQuestion;
            $answerQuestion->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswerQuestion(AnswerQuestion $answerQuestion): self
    {
        if ($this->answerQuestions->removeElement($answerQuestion)) {
            // set the owning side to null (unless already changed)
            if ($answerQuestion->getQuestion() === $this) {
                $answerQuestion->setQuestion(null);
            }
        }

        return $this;
    }
    
    /**
     * Method getPoints
     *
     * @return int
     */
    public function getPoints(): int{
        $points = 0;
        foreach ($this->choices as $choice){
            $points += $choice->getValue();
        }
        return $points;
    }
}
