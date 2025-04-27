<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use App\Entity\Livres;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class  LivresFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {  $faker = Factory::create('fr_FR');

        for($j=1;$j<=5;$j++){
            $categorie = new Categories();
            $libelle=$faker->name;
            $categorie->setLibelle($libelle)
                     ->setSlug(strtolower(str_replace(' ', '-', $libelle)))
                ->setDescription($faker->text);
            $manager->persist($categorie);

           for ($i = 1; $i <= random_int(15,20); $i++) {
           $livre = new Livres();
           $titre = $faker->name();
           $livre->setTitre($titre)
                 ->setSlug(strtolower(str_replace(' ', '-', $titre)))
                 ->setIsbn($faker->isbn13())
                    ->setImage("https://picsum.photos/300/?id=".$i)
            ->setResume($faker->text)
            ->setEditeur($faker->company)
            ->setDateEdition($faker->dateTimeBetween('-5 years', 'now'))
            ->setPrix($faker->randomFloat(2,10,700))
               ->setCat($categorie);

        $manager->persist($livre);


    }}
        $manager->flush();
    }
}
