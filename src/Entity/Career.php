<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CareerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CareerRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['career:read']],
)]
class Career
{
    #[Groups(['career:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['career:read', 'weight:read'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['career:read'])]
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    /**
     * @var Collection<int, QuestionCareerWeight>
     */
    #[ORM\OneToMany(targetEntity: QuestionCareerWeight::class, mappedBy: 'career')]
    private Collection $questionCareerWeights;

    public function __construct()
    {
        $this->questionCareerWeights = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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
            $questionCareerWeight->setCareer($this);
        }

        return $this;
    }

    public function removeQuestionCareerWeight(QuestionCareerWeight $questionCareerWeight): static
    {
        if ($this->questionCareerWeights->removeElement($questionCareerWeight)) {
            // set the owning side to null (unless already changed)
            if ($questionCareerWeight->getCareer() === $this) {
                $questionCareerWeight->setCareer(null);
            }
        }

        return $this;
    }
}
