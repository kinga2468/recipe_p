<?php
/**
 * Ingredient fixtures.
 */

namespace App\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Ingredient;


/**
 * Class IngredientFixtures.
 */
class IngredientFixtures extends AbstractBaseFixtures
{
    /**
     * Load.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->sentence);
            $ingredient->setAmount($this->faker->sentence($nbWords = 1));
            $this->manager->persist($ingredient);
        }

        $manager->flush();
    }
}