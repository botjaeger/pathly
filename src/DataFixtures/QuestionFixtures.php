<?php

namespace App\DataFixtures;

use App\Entity\Career;
use App\Entity\Question;
use App\Entity\QuestionCareerWeight;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class QuestionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $rows = [
            [
                'I enjoy drawing characters or environments from imagination.',
                [
                    '2D Animator' => [90, null],
                    'Digital Artist' => [70, 20],
                    'UI/UX Designer' => [null, 50],
                ],
            ],
            [
                'I like creating or animating objects in 3D space.',
                [
                    '3D Animator' => [95, 5],
                    'Motion Designer' => [65, null],
                    'Game Designer' => [40, 60],
                ],
            ],
            [
                'I enjoy transforming static images into dynamic motion graphics.',
                [
                    'Motion Designer' => [85, 15],
                    '2D Animator' => [60, null],
                    'Digital Artist' => [null, 55],
                ],
            ],
            [
                'I get excited about leading a team to tell a story on screen.',
                [
                    'Film Director' => [100, null],
                    'Post Production Professional' => [70, 20],
                    'Sound Engineer' => [null, 45],
                ],
            ],
            [
                'I like cutting raw footage into a polished video.',
                [
                    'Post Production Professional' => [90, 10],
                    'Film Director' => [null, 25],
                    'Motion Designer' => [30, null],
                ],
            ],
            [
                'I’m sensitive to sound details like rhythm, tone, or audio quality.',
                [
                    'Sound Engineer' => [95, null],
                    'Post Production Professional' => [65, 20],
                    'Film Director' => [40, null],
                ],
            ],
            [
                'I like inventing game rules, levels, or player experiences.',
                [
                    'Game Designer' => [100, 0],
                    'Game Developer' => [70, null],
                    '3D Animator' => [null, 45],
                ],
            ],
            [
                'I enjoy solving technical problems by writing code.',
                [
                    'Game Developer' => [95, 5],
                    'UI/UX Designer' => [65, null],
                    'Game Designer' => [null, 50],
                ],
            ],
            [
                'I enjoy experimenting with layouts and how people use interfaces.',
                [
                    'UI/UX Designer' => [90, null],
                    'Motion Designer' => [70, 20],
                    'Digital Artist' => [35, null],
                ],
            ],
            [
                'I enjoy paying attention to small details until something feels realistic.',
                [
                    '3D Animator' => [100, null],
                    'Post Production Professional' => [70, 20],
                    '2D Animator' => [null, 45],
                ],
            ],
        ];

        foreach ($rows as [$text, $careerWeights]) {
            $question = new Question();
            $question->setMessage($text);
            $question->setActive(true);
            $manager->persist($question);

            foreach ($careerWeights as $careerName => [$yes, $no]) {
                /** @var Career $career */
                $career = $this->getReference('career_' . $careerName, Career::class);

                if (!$career) {
                    continue;
                }

                $qc = new QuestionCareerWeight();
                $qc->setQuestion($question);
                $qc->setCareer($career);
                $qc->setYesWeight($yes);
                $qc->setNoWeight($no);

                $question->addQuestionCareerWeight($qc);
                $career->addQuestionCareerWeight($qc);

                $manager->persist($qc);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CareerFixtures::class];
    }
}
