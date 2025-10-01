<?php

namespace App\Dto;

class AssessmentResponseDto
{
    /** @param array<string,int> $totals @param array<string,float> $percentages */
    public function __construct(
        public array $totals,
        public array $percentages,
        public array $top,
    ) {}
}
