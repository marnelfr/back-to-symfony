<?php

namespace App\Repository;

use App\Entity\Answer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Answer>
 *
 * @method Answer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Answer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Answer[]    findAll()
 * @method Answer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Answer::class);
    }

    public function add(Answer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Answer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public static function createApprovedCriteria(): Criteria
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq('status', Answer::STATUS_APPROVED));
    }

    /**
     * @return Answer[]
     */
    public function findBestAnswers(string $filter = null, int $max = 10): array
    {
        $query = $this->createQueryBuilder('a')
            ->addCriteria(self::createApprovedCriteria())
            ->orderBy('a.votes', 'DESC')
            ->innerJoin('a.question', 'q')
            ->addSelect('q');

        if($filter) {
            $query->andWhere('a.content LIKE :searchTerm OR q.question LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $filter . '%');
        }

        return $query
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }
}
