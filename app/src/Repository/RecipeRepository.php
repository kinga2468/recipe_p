<?php
/**
 * Recipe repository.
 */
namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\QueryBuilder;

/**
 *  RecipeRepository class.
 *
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    /**
     * RecipeRepository constructor.
     *
     * @param \Symfony\Bridge\Doctrine\RegistryInterface $registry Registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * Query all records.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('r.updatedAt', 'DESC');
    }

    /**
     * Get or create new query builder.
     *
     * @param \Doctrine\ORM\QueryBuilder|null $queryBuilder Query builder
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?: $this->createQueryBuilder('r');
    }

    /**
     * @return mixed
     * funckja zwracająca 8 ostatnio dodanych przepisów, na stronę główną
     */
    public function findRecipeByUpdateDate()
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.createdAt', 'DESC')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return mixed
     * funckja zwracająca wszysktie przepisy posortowane po dacie aktualizacji, na podstanę wszystkiech przepisów
     */
    public function allRecipeByUpdateDate()
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.updatedAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return mixed
     * funkcja zwracająca najbardziej popularne tagi, na podstronie z przepisami
     */
    public function findMostPopularTag()
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.tags','g')
            ->addSelect('g')
            ->select('g.title', 'g.id')
            ->groupBy('g.id')
            ->orderBy('COUNT(r.id)', 'DESC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult()
            ;
    }

//     /**
//      * @return Recipe[] Returns an array of Recipe objects
//      */
//    public function findByExampleField($value)
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }


    /*
    public function findOneBySomeField($value): ?Recipe
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Save record.
     *
     * @param \App\Entity\Recipe $recipe Recipe entity
     *
     * @return void
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Recipe $recipe): void
    {
        $this->_em->persist($recipe);
        $this->_em->flush($recipe);
    }

    /**
     * Delete record.
     *
     * @param \App\Entity\Recipe $recipe Recipe entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Recipe $recipe): void
    {
        $this->_em->remove($recipe);
        $this->_em->flush($recipe);
    }


    /*
     *  funkcja znajdujące komentarze do danego przepisu
     */
    public function findRecipeComments($recipeId)
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.comments','c')
            ->andWhere('r.id = :val')
            ->setParameter('val', $recipeId)
            ->select('c.id, c.text, c.createdAt')
            ->getQuery()
            ->getResult();

    }
}
