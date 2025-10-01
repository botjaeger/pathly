<?php

namespace App\Entity;


use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Dto\AssessmentResponseDto;
use App\State\AssessmentProcessor;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/assessments',
            output: AssessmentResponseDto::class,
            processor: AssessmentProcessor::class
        ),
        new GetCollection(),
    ]
)]
class Assessment
{
    /**
     * @var array<string,string>
     */
    public array $questionAnswers = [];

    public function __construct(array $data)
    {
        // if payload is just a flat map → use it directly
        $this->questionAnswers = $data;
    }
}
