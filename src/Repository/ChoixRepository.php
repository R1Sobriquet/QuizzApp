<?php

namespace App\Repository;

use App\Entity\Choix;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Choix>
 *
 * @method Choix|null find($id, $lockMode = null, $lockVersion = null)
 * @method Choix|null findOneBy(array $criteria, array $orderBy = null)
 * @method Choix[]    findAll()
 * @method Choix[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChoixRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Choix::class);
    }

    /**
     * Trouve le choix correct pour une question
     */
    public function findCorrectChoix(int $questionId): ?Choix
    {
        return $this->createQueryBuilder('c')
            ->where('c.question = :questionId')
            ->andWhere('c.estCorrect = true')
            ->setParameter('questionId', $questionId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}