<?php

namespace App\DataFixtures;

use App\Entity\Career;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CareerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $careers = [
            ['2D Animator', 'Creates 2D animations for film, games, and digital media.'],
            ['3D Animator', 'Specializes in 3D modeling, rigging, and animation.'],
            ['Digital Artist', 'Focuses on digital illustration, concept art, and visual design.'],
            ['Film Director', 'Oversees the creative and production process of films or video projects.'],
            ['Game Designer', 'Designs gameplay systems, levels, and user experiences for games.'],
            ['Game Developer', 'Programs and builds the technical foundation of video games.'],
            ['Motion Designer', 'Creates engaging motion graphics and visual effects.'],
            ['Post Production Professional', 'Works on video editing, color grading, and compositing in post-production.'],
            ['Sound Engineer', 'Handles audio recording, mixing, and sound design.'],
            ['UI/UX Designer', 'Designs intuitive user interfaces and engaging user experiences.'],
        ];

        foreach ($careers as [$name, $description]) {
            $career = new Career();
            $career->setName($name);
            $career->setDescription($description);
            $manager->persist($career);

            $this->addReference('career_' . $name, $career);
        }

        $manager->flush();
    }
}
