<?php
/**
 * Tag fixture.
 */
namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class TagFixtures.
 */
class TagFixtures extends AbstractBaseFixtures
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
            $tag = new Tag();
            $tag->setTitle($this->faker->word);
            $tag->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $tag->setUpdatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));

            $this->manager->persist($tag);
        }

        $manager->flush();
    }
}