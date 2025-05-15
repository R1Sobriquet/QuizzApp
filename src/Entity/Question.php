<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $texte = null;

    #[ORM\Column]
    private ?int $niveauDifficulte = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Choix::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $choix;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: QuizQuestion::class, orphanRemoval: true)]
    private Collection $quizQuestions;

    public function __construct()
    {
        $this->choix = new ArrayCollection();
        $this->quizQuestions = new ArrayCollection();
        $this->dateCreation = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): self
    {
        $this->texte = $texte;
        return $this;
    }

    public function getNiveauDifficulte(): ?int
    {
        return $this->niveauDifficulte;
    }

    public function setNiveauDifficulte(int $niveauDifficulte): self
    {
        $this->niveauDifficulte = $niveauDifficulte;
        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;
        return $this;
    }

    /**
     * @return Collection<int, Choix>
     */
    public function getChoix(): Collection
    {
        return $this->choix;
    }

    public function addChoix(Choix $choix): self
    {
        if (!$this->choix->contains($choix)) {
            $this->choix->add($choix);
            $choix->setQuestion($this);
        }

        return $this;
    }

    public function removeChoix(Choix $choix): self
    {
        if ($this->choix->removeElement($choix)) {
            // set the owning side to null (unless already changed)
            if ($choix->getQuestion() === $this) {
                $choix->setQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, QuizQuestion>
     */
    public function getQuizQuestions(): Collection
    {
        return $this->quizQuestions;
    }

    public function addQuizQuestion(QuizQuestion $quizQuestion): self
    {
        if (!$this->quizQuestions->contains($quizQuestion)) {
            $this->quizQuestions->add($quizQuestion);
            $quizQuestion->setQuestion($this);
        }

        return $this;
    }

    public function removeQuizQuestion(QuizQuestion $quizQuestion): self
    {
        if ($this->quizQuestions->removeElement($quizQuestion)) {
            // set the owning side to null (unless already changed)
            if ($quizQuestion->getQuestion() === $this) {
                $quizQuestion->setQuestion(null);
            }
        }

        return $this;
    }
    
    public function getBonneReponse(): ?Choix
    {
        foreach ($this->choix as $choix) {
            if ($choix->isEstCorrect()) {
                return $choix;
            }
        }
        return null;
    }
    
    public function __toString(): string
    {
            return substr($this->texte, 0, 50) . (strlen($this->texte) > 50 ? '...' : '');
        }
    }