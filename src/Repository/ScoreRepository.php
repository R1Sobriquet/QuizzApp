<?php

namespace App\Repository;

use App\Entity\Score;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Score>
 *
 * @method Score|null find($id, $lockMode = null, $lockVersion = null)
 * @method Score|null findOneBy(array $criteria, array $orderBy = null)
 * @method Score[]    findAll()
 * @method Score[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Score::class);
    }

    /**
     * Trouve les meilleurs scores d'un utilisateur
     */
    public function findBestScores(Utilisateur $utilisateur, int $limit = 5): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.utilisateur = :utilisateur')
            ->setParameter('utilisateur', $utilisateur)
            ->orderBy('s.note', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve le score moyen d'un utilisateur
     */
    public function findAverageScore(Utilisateur $utilisateur): ?float
    {
        $result = $this->createQueryBuilder('s')
            ->select('AVG(s.note) as averageScore')
            ->where('s.utilisateur = :utilisateur')
            ->setParameter('utilisateur', $utilisateur)
            ->getQuery()
            ->getSingleScalarResult();
            
        return $result ? (float) $result : null;
    }
}