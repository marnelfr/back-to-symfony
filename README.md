## Doctrine
- ``doctrine:query:sql "select * from questions"`` used to 
make request to the database base.
- ``doctrine:migration:list`` displays the list of migrations 
and their status
- ``throw $this->createNotFoundException($message)`` can be 
used to trigger a 404 page. The ``$message`` it receive it's
only seen by developer.



## Param converter
Using the **sensio/framework-extra-bundle**, we can make queries directly based on our 
path wildcards.\
Installation: ``composer require sensio/framework-extra-bundle``\
````php
#[Route('/questions/{slug}/{id}')]
public function show(Question $question): Response
//find one Question where the slug and id match.
````

## Controller arguments
- An argument those name matches a route wildcard
- Autowired a service via its type-hint
- Type-hint an entity class to automatically query about it
- Type-hint the Request class to automatically get the 
Request object.
-


## Data Fixtures
Installation: ``composer req orm-fixtures --dev``\
Can be used to populate our database in order to test our code.
Using ``doctrine:fixtures:load``, we can load our fixtures.

### Using Factory
Installation: ``composer req zenstruck/foundry --dev``\
It brings us the ``make:factory`` command. We can then create
our factory using [FakerPHP](https://github.com/FakerPHP/Faker)
and use them in our **DataFixtures**.\
While using **fondry** to define our relationship, always associate 
factory as relational entity. This avoid useless data insertion 
when we overwrite the associated factory value.

## [Doctrine extension](https://github.com/doctrine-extensions/DoctrineExtensions)
We've got some extension for doctrine allowing to perform easily some
actions such as 
- updates date fields on create, update and even property change.
- urlizes specified fields into single unique slug
- very handy solution for translating records into different languages
- 

Let's use a tiny layer of this bundle called [StofDoctrineExtensionsBundle](https://symfony.com/bundles/StofDoctrineExtensionsBundle/current/index.html)


## Entity 
``EXTRA_LAZY`` fetch make doctrine only query a ``count(*)`` when
we only access the ``length`` of a collection from an entity.
However, this may cause more queries from Doctrine in case we access
the length of the collection before using its properties.

``#[ORM\OrderBy(['createdAt' => 'DESC'])]`` can be used to ordered
a collection get from a doctrine relation.

### Getter usage
Don't hesitate to use and entity getter to simplify codes. 
Methods like ``$answer->isApproved()``, 
``$questions->getApprovedAnswers()`` should be added and used 
instead of only using the provided ``$questions->getAnswers()``.

## Criteria
Can be used to add ``where`` statements to our queries while using 
the ``matching()`` method on a collection. It's better than just
using the ``filter()`` method.
````php 
/src/Repository/AnswerRepository
public static function createApprovedCriteria(): Criteria
{
    return Criteria::create()
        ->andWhere(Criteria::expr()->eq('status', Answer::STATUS_APPROVED));
}

/src/Entity/Question
public function getApprovedAnswers(): Collection
{
    return $this->answers->matching(AnswerRepository::createApprovedCriteria());
}
````

Criteria can be used with the ``queryBuilder`` thanks to the 
``addCriteria()`` method.

## N+1 problem
One way you can fix the **N+1 problem** is using **innerJoin** while 
querying.

## [PagerFantaBundle](https://www.babdev.com/open-source/packages/pagerfantabundle/docs)
Instead of using the [KnpPaginatorBundle](https://github.com/KnpLabs/KnpPaginatorBundle),
this is also a great bundle to use for pagination.\
To use it, we need to install the bundle: ``composer require babdev/pagerfanta-bundle``\
Now, we have to install the QueryAdapter corresponding to our ORM
``composer req pagerfanta/doctrine-orm-adapter`` then we can create 
our pager this way:
````php 
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

$queryBuilder = $this->repository->creatQueryBuilderForQuestions();
$pager = new Pagerfanta(new QueryAdapter($queryBuilder));
$pager->setMaxPerPage(5);
$pager->setCurrentPage($request->query->get('page', 1));
````
We can use the twitter_bootstrap5 view if we install the twig function
using ``composer req pagerfanta/twig``


# [API Platform](https://github.com/marnelfr/symfony-demo/blob/apiplatform/README.md)


## [Testing](https://symfony.com/doc/current/testing.html)
Installation: ``composer req test --dev``\
I've got to install [LiipTestFixturesBundle](https://github.com/liip/LiipTestFixturesBundle) as well: 
``composer require --dev liip/test-fixtures-bundle:^2.0.0`` and 
enable the Bundle [only in the test environment](https://github.com/liip/LiipTestFixturesBundle/blob/2.x/doc/installation.md)





