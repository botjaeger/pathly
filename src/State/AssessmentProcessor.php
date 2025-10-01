<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\AssessmentResponseDto;
use App\Service\AssessmentScoring;

final readonly class AssessmentProcessor implements ProcessorInterface
{
    public function __construct(private AssessmentScoring $scoring) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): AssessmentResponseDto
    {
        $result = $this->scoring->score($data);

        return new AssessmentResponseDto(
            $result['totals'],
            $result['percentages'],
            $result['top'],
        );
    }
}
