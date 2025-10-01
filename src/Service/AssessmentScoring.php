<?php

namespace App\Service;

use ApiPlatform\Metadata\IriConverterInterface;
use App\Entity\Assessment;
use App\Entity\Question;
use App\Entity\QuestionCareerWeight;
use App\Repository\CareerRepository;
use App\Util\LikertAnswerEnum;
use Exception;

readonly class AssessmentScoring
{
    public function __construct(
        private IriConverterInterface $iriConverter,
        private CareerRepository $careers,
    ) {
    }

    /**
     * @param Assessment $object ["/api/questions/1" => "yes", ...]
     * @return array{
     *   totals: array<string,int>,
     *   percentages: array<string,float>,
     *   top: array<int,array{name:string,description:string,percentage:float}>
     * }
     */
    public function score(Assessment $object): array
    {
        $answers = $object->questionAnswers;
        $totals = [];

        foreach ($this->careers->findAll() as $career) {
            $totals[$career->getName()] = 0;
        }

        if (empty($answers)) {
            return [
                'totals' => $totals,
                'percentages' => array_fill_keys(array_keys($totals), 0.0),
                'top' => [],
            ];
        }

        foreach ($answers as $iri => $ansStr) {
            try {
                /** @var Question $question */
                $question = $this->iriConverter->getResourceFromIri($iri);
            } catch (Exception) {
                continue;
            }

            $answer = LikertAnswerEnum::fromString($ansStr);
            $multiplier = $answer->score();

            /** @var QuestionCareerWeight $qc */
            foreach ($question->getQuestionCareerWeights() as $qc) {
                $career = $qc->getCareer();
                if (!$career) {
                    continue;
                }
                $totals[$career->getName()] += $qc->getWeight() * $multiplier;
            }
        }

        $sum = array_sum($totals);
        $percentages = array_map(
            static fn($v) => $sum > 0 ? round(($v / $sum) * 100, 2) : 0.0,
            $totals
        );

        arsort($percentages);
        $top = [];
        foreach (array_slice($percentages, 0, 3, true) as $name => $pct) {
            $career = $this->careers->findOneBy(['name' => $name]);
            $top[] = [
                'name' => $name,
                'description' => $career?->getDescription(),
                'percentage' => $pct,
            ];
        }

        return [
            'totals' => $totals,
            'percentages' => $percentages,
            'top' => $top,
        ];
    }
}
