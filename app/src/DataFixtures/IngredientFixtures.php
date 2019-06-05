<?php
/**
 * Ingredient fixture.
 */
namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class IngredientFixtures.
 */
class IngredientFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager Object manager
     *
     * @return void
     */
    public function loadData(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $ingredient = new Ingredient();
            $ingredient->setTitle($this->faker->word);

            $this->manager->persist($ingredient);
        }

        $manager->flush();
    }
}