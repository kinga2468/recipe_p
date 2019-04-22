<?php
/**
 * Recipe fixtures.
 */

namespace App\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Recipe;

/**
 * Class RecipeFixtures.
 */
class RecipeFixtures extends AbstractBaseFixtures
{
    /**
     * Load.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $recipe = new Recipe();
            $recipe->setTitle($this->faker->sentence);
            $recipe->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $recipe->setUpdatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $recipe->setDescription($this->faker->sentence);
            $recipe->setPhoto($this->faker->sentence);
            $recipe->setTime(mt_rand(10, 120));
            $recipe->setPeopleAmount(mt_rand(1, 4));
            $this->manager->persist($recipe);
        }

        $manager->flush();
    }
}
