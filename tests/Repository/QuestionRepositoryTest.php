<?php

namespace App\Tests\Repository;

use App\DataFixtures\QuestionFixtures;
use App\Repository\QuestionRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class QuestionRepositoryTest extends KernelTestCase
{
    protected AbstractDatabaseTool $databaseTool;

    public function setUp(): void
    {
        parent::setUp();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function testCount(): void
    {
        self::bootKernel();
        $this->databaseTool->loadFixtures([
            QuestionFixtures::class
        ]);
        $questions = self::getContainer()->get(QuestionRepository::class)->count([]);
        $this->assertEquals(20, $questions);
    }
}
