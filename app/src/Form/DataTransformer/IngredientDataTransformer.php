<?php
/**
 * Ingredient data transformer.
 */

namespace App\Form\DataTransformer;

use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class IngredientsDataTransformer.
 */
class IngredientDataTransformer implements DataTransformerInterface
{
    /**
     * Ingredient repository.
     *
     * @var \App\Repository\IngredientRepository|null
     */
    private $repository = null;

    /**
     * IngredientsDataTransformer constructor.
     *
     * @param \App\Repository\IngredientRepository $repository Ingredient repository
     */
    public function __construct(IngredientRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Transform array of ingredients to string of names.
     *
     * @param \Doctrine\Common\Collections\Collection $ingredients Ingredients entity collection
     *
     * @return string Result
     */
    public function transform($ingredients)
    {
//        dump($ingredients);
//        if (null == $ingredients) {
//            return '';
//        }
//
//        $ingredientNames = [];
//
//        foreach ($ingredients as $ingredient) {
//            $ingredientNames[] = $ingredient->getName();
//        }
//
//        return implode(',', $ingredientNames);
        return $ingredients;
    }

    /**
     * Transform string of ingredient names into array of ingredient entities.
     *
     * @param string $value String of ingredient names
     *
     * @return array Result
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function reverseTransform($value): array
    {
//        dump($value);
//        die();
//        if($value != null){
            foreach ($value as $ingredientName) {
                $ingredientName = $ingredientName->getName();
                if ('' !== trim($ingredientName)) {
                    $ingredient = $this->repository->findOneByName(strtolower($ingredientName));
                    if (null == $ingredient) {
                        $ingredient = new Ingredient();
                        $ingredient->setName($ingredientName);
                        $this->repository->save($ingredient);
                    }
                    $ingredients[] = $ingredient;
                }
            }
            return $ingredients;
//        }
//        else{
//            return [];
//        }

    }
}