<?php
/**
 * Recipe fixtures.
 */

namespace App\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Recipe;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class RecipeFixtures.
 */
class RecipeFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(20, 'recipes', function ($i) {
            $recipe = new Recipe();
            $recipe->setTitle($this->faker->sentence);
            $recipe->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $recipe->setUpdatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $recipe->setDescription($this->faker->sentence);
            $recipe->setPhoto($this->faker->sentence);
            $recipe->setTime(mt_rand(10, 120));
            $recipe->setPeopleAmount(mt_rand(1, 4));
//            $tags = $this->getRandomReferences(
//                'tags',
//                $this->faker->numberBetween(0, 5)
//            );
//
//            foreach ($tags as $tag) {
//                $recipe->addTag($tag);
//            }

            $recipe->setAuthor($this->getRandomReference('users'));
            return $recipe;
        });

        $manager->flush();
    }
    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array Array of dependencies
     */
    public function getDependencies(): array
    {
        return [IngredientFixtures::class, TagFixtures::class, UserFixtures::class];
    }
}
