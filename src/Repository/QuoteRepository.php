<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Quote;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quote>
 *
 * @method Quote|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quote|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quote[]    findAll()
 * @method Quote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quote::class);
    }

    public function save(Quote $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Quote $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLastCreatedByUser(User $author, int $limit = 5): array
    {
        return $this
            ->createQueryBuilder('q')
            ->select('q, c, a')
            ->leftJoin('q.category', 'c')
            ->join('q.author', 'a')
            ->where('q.author = :author')
            ->setParameter('author', $author)
            ->orderBy('q.date_creation', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function random(Category $category = null): ?Quote
    {
        $queryBuilder = $this->createQueryBuilder('q')
            ->select('q.id');

        if (null !== $category) {
            $queryBuilder->where('q.category = :category')
                ->setParameter('category', $category);
        }

        $ids = $queryBuilder->getQuery()->getScalarResult();
        if (empty($ids)) {
            return null;
        }

        $ids = array_column($ids, 'id');
        $randomId = $ids[array_rand($ids)];

        return $this->find($randomId);
    }

//    /**
//     * @return Quote[] Returns an array of Quote objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Quote
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
