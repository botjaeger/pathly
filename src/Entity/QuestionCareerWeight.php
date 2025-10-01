<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\QuestionCareerWeightRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: QuestionCareerWeightRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['weight:read']],
)]
#[ApiFilter(SearchFilter::class, properties: ['question' => 'exact', 'career' => 'exact'])]
class QuestionCareerWeight
{
    #[Groups(['weight:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['weight:read'])]
    #[ORM\ManyToOne(inversedBy: 'questionCareerWeights')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    #[Groups(['weight:read', 'question:read'])]
    #[ORM\ManyToOne(inversedBy: 'questionCareerWeights')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Career $career = null;

    #[Groups(['weight:read', 'question:read'])]
    #[ORM\Column]
    private ?int $weight = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getCareer(): ?Career
    {
        return $this->career;
    }

    public function setCareer(?Career $career): static
    {
        $this->career = $career;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): static
    {
        $this->weight = $weight;

        return $this;
    }
}
