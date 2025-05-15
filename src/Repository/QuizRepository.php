<?php

namespace App\Repository;

use App\Entity\Quiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quiz>
 *
 * @method Quiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quiz[]    findAll()
 * @method Quiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quiz::class);
    }

    /**
     * Trouve les quiz qui contiennent des questions de la catégorie spécifiée
     */
    public function findByCategory(int $categoryId): array
    {
        $qb = $this->createQueryBuilder('q')
            ->join('q.quizQuestions', 'qq')
            ->join('qq.question', 'quest')
            ->join('quest.categorie', 'cat')
            ->where('cat.id = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->orderBy('q.dateCreation', 'DESC')
            ->distinct();
            
        return $qb->getQuery()->getResult();
    }

    /**
     * Trouve les quiz récents
     */
    public function findRecent(int $limit = 5): array
    {
        return $this->createQueryBuilder('q')
            ->orderBy('q.dateCreation', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}