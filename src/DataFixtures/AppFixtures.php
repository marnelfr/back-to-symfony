<?php

namespace App\DataFixtures;

use App\Factory\AnswerFactory;
use App\Factory\QuestionFactory;
use App\Factory\QuestionTagFactory;
use App\Factory\TagFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        TagFactory::createMany(100);

        $questions = QuestionFactory::createMany(20);

        QuestionTagFactory::createMany(100, function() {
            return [
                'question' => QuestionFactory::random(),
                'tag' => TagFactory::random()
            ];
        });

        QuestionFactory::new()->unpublished()->many(5)->create();

        AnswerFactory::createMany(100, function () use ($questions) {
            return [
                'question' => $questions[array_rand($questions)]
            ];
        });

        AnswerFactory::new(function() use ($questions) {
            return [
                'question' => $questions[array_rand($questions)]
            ];
        })->unapproved()->many(20)->create();
    }
}
