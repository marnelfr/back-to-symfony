<?php

namespace App\Factory;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Question>
 *
 * @method static Question|Proxy createOne(array $attributes = [])
 * @method static Question[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Question|Proxy find(object|array|mixed $criteria)
 * @method static Question|Proxy findOrCreate(array $attributes)
 * @method static Question|Proxy first(string $sortedField = 'id')
 * @method static Question|Proxy last(string $sortedField = 'id')
 * @method static Question|Proxy random(array $attributes = [])
 * @method static Question|Proxy randomOrCreate(array $attributes = [])
 * @method static Question[]|Proxy[] all()
 * @method static Question[]|Proxy[] findBy(array $attributes)
 * @method static Question[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Question[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static QuestionRepository|RepositoryProxy repository()
 * @method Question|Proxy create(array|callable $attributes = [])
 */
final class QuestionFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    public function unpublished() {
        return $this->addState(['askedAt' => null]);
    }

    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->text(30),
            'votes' => self::faker()->numberBetween(-20, 30),
            'question' => self::faker()->paragraph(rand(1,4)),
            'askedAt' => new \DateTimeImmutable(self::faker()->dateTimeBetween('-110 days', '-1 minute')->format('Y-m-d'))
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
             ->afterInstantiate(function(Question $question): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Question::class;
    }
}