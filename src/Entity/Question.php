<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['question:read']],
)]
#[ApiFilter(BooleanFilter::class, properties: ['active'])]
class Question
{
    #[Groups(['question:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['question:read'])]
    #[ORM\Column(length: 255)]
    private ?string $message = null;

    /**
     * @var Collection<int, QuestionCareerWeight>
     */
    #[Groups(['question:read'])]
    #[ApiProperty(writableLink: true)]
    #[ORM\OneToMany(targetEntity: QuestionCareerWeight::class, mappedBy: 'question', cascade: ['persist'], orphanRemoval: true)]
    private Collection $questionCareerWeights;

    #[Groups(['question:read'])]
    #[ORM\Column]
    private ?bool $active = null;

    public function __construct()
    {
        $this->questionCareerWeights = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return Collection<int, QuestionCareerWeight>
     */
    public function getQuestionCareerWeights(): Collection
    {
        return $this->questionCareerWeights;
    }

    public function addQuestionCareerWeight(QuestionCareerWeight $questionCareerWeight): static
    {
        if (!$this->questionCareerWeights->contains($questionCareerWeight)) {
            $this->questionCareerWeights->add($questionCareerWeight);
            $questionCareerWeight->setQuestion($this);
        }

        return $this;
    }

    public function removeQuestionCareerWeight(QuestionCareerWeight $questionCareerWeight): static
    {
        if ($this->questionCareerWeights->removeElement($questionCareerWeight)) {
            // set the owning side to null (unless already changed)
            if ($questionCareerWeight->getQuestion() === $this) {
                $questionCareerWeight->setQuestion(null);
            }
        }

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }
}
