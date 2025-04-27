<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Categories;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $category = new Categories();
            $category->setLibelle($faker->word)
            ->setSlug($faker->slug)
            ->setDescription($faker->text)
            ;

            $manager->persist($category);
        }


        $manager->flush();
    }
}
