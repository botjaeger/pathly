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
                ['2D Animator' => 10, 'Digital Artist' => 5, 'UI/UX Designer' => 1],
            ],
            [
                'I like creating or animating objects in 3D space.',
                ['3D Animator' => 10, 'Motion Designer' => 5, 'Game Developer' => 1],
            ],
            [
                'I enjoy transforming static images into dynamic motion graphics.',
                ['Motion Designer' => 10, '2D Animator' => 5, 'Film Director' => 1],
            ],
            [
                'I get excited about leading a team to tell a story on screen.',
                ['Film Director' => 10, 'Post Production Professional' => 5, 'Sound Engineer' => 1],
            ],
            [
                'I like cutting raw footage into a polished video.',
                ['Post Production Professional' => 10, 'Film Director' => 5, 'Motion Designer' => 1],
            ],
            [
                'I’m sensitive to sound details like rhythm, tone, or audio quality.',
                ['Sound Engineer' => 10, 'Post Production Professional' => 5, 'Game Designer' => 1],
            ],
            [
                'I like inventing game rules, levels, or player experiences.',
                ['Game Designer' => 10, 'Game Developer' => 5, 'UI/UX Designer' => 1],
            ],
            [
                'I enjoy solving technical problems by writing code.',
                ['Game Developer' => 10, 'UI/UX Designer' => 5, '3D Animator' => 1],
            ],
            [
                'I enjoy experimenting with layouts and how people use interfaces.',
                ['UI/UX Designer' => 10, 'Digital Artist' => 5, 'Motion Designer' => 1],
            ],
            [
                'I enjoy paying attention to small details until something feels realistic.',
                ['3D Animator' => 10, 'Post Production Professional' => 5, '2D Animator' => 1],
            ],
        ];

        foreach ($rows as [$text, $weights]) {
            $question = new Question();
            $question->setMessage($text);
            $question->setActive(true);

            foreach ($weights as $careerName => $weight) {
                /** @var Career $career */
                $career = $this->getReference('career_' . $careerName, Career::class);

                $link = new QuestionCareerWeight();
                $link->setQuestion($question);
                $link->setCareer($career);
                $link->setWeight($weight);

                $question->addQuestionCareerWeight($link);
                $career->addQuestionCareerWeight($link);

                $manager->persist($link);
            }

            $manager->persist($question);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CareerFixtures::class];
    }
}
