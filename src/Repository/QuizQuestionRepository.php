<?php

namespace App\Repository;

use App\Entity\QuizQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuizQuestion>
 *
 * @method QuizQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizQuestion[]    findAll()
 * @method QuizQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizQuestion::class);
    }

    /**
     * Trouve les questions d'un quiz dans l'ordre
     */
    public function findByQuizOrdered(int $quizId): array
    {
        return $this->createQueryBuilder('qq')
            ->where('qq.quiz = :quizId')
            ->setParameter('quizId', $quizId)
            ->orderBy('qq.ordre', 'ASC')
            ->getQuery()
            ->getResult();
    }
}