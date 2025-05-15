<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Question>
 *
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    /**
     * Trouve les questions par niveau de difficulté
     */
    public function findByDifficulte(int $niveau): array
    {
        return $this->createQueryBuilder('q')
            ->where('q.niveauDifficulte = :niveau')
            ->setParameter('niveau', $niveau)
            ->orderBy('q.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les questions par catégorie
     */
    public function findByCategorie(int $categorieId): array
    {
        return $this->createQueryBuilder('q')
            ->where('q.categorie = :categorieId')
            ->setParameter('categorieId', $categorieId)
            ->orderBy('q.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }
}