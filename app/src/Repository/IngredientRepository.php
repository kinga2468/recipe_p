<?php

namespace App\Repository;

use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Ingredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ingredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ingredient[]    findAll()
 * @method Ingredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

    /**
     * Query all records.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('i.title', 'DESC');
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
        return $queryBuilder ?: $this->createQueryBuilder('i');
    }
    /**
     * Save record.
     *
     * @param \App\Entity\Ingredient $ingredient Ingredient entity
     *
     * @return void
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Ingredient $ingredient): void
    {
        $this->_em->persist($ingredient);
        $this->_em->flush($ingredient);
    }
    /**
     * Delete record.
     *
     * @param \App\Entity\Ingredient $ingredient Ingredient entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Ingredient $ingredient): void
    {
        $this->_em->remove($ingredient);
        $this->_em->flush($ingredient);
    }

    /**
     * znajduje przepisy o wyszukiwanym składniku
     * @param string $search
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function findRecipeByIngredient($search): QueryBuilder
    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.id = :val')
//            ->setParameter('val', $search)
//            ->innerJoin('i.recipes','r')
//            ->addSelect('r')
//            ->select('r.title', 'r.id', 'r.photo, r.time, r.people_amount');

        return $this->getOrCreateQueryBuilder()
            ->where('i.title LIKE :search')
            ->setParameter('search', '%'.$search.'%')
            ->orderBy("LOCATE(:pos, i.title), i.title")
            ->setParameter('pos', $search);
    }
}








//        return $this->createQueryBuilder('i')
//            ->andWhere('i.id = :val')
//            ->setParameter('val', $search)
//            ->innerJoin('i.recipes','r')
//            ->addSelect('r')
//            ->select('r.title', 'r.id', 'r.photo, r.time, r.people_amount');