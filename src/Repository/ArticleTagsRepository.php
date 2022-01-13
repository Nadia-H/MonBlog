<?php

namespace App\Repository;

use App\Entity\ArticleTags;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ArticleTags|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleTags|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleTags[]    findAll()
 * @method ArticleTags[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleTagsRepository extends ServiceEntityRepository //ce repository sert à recupérer les données de la table articlesTags dans la bd
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleTags::class);
    }

}
