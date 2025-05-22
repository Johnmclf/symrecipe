<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use Faker\Generator;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }
    
    public function load(ObjectManager $manager): void
    {
        $ingredients = [];

        for ($i = 1; $i <= 50; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word())
                       ->setPrice(mt_rand(1, 199));
            $manager->persist($ingredient);
            $ingredients[] = $ingredient;
        }

        for ($j = 1; $j <= 25; $j++) {
            $recipe = new Recipe();
            $recipe->setName($this->faker->words(3, true)) // ex: "soupe de légumes"
                   ->setTime($this->faker->optional()->numberBetween(1, 1440))
                   ->setNbPersons($this->faker->optional()->numberBetween(1, 50))
                   ->setDifficulty($this->faker->optional()->numberBetween(1, 5))
                   ->setDescription($this->faker->paragraph())
                   ->setPrice($this->faker->optional()->randomFloat(2, 0, 1000))
                   ->setIsFavorite($this->faker->boolean());

            $usedIngredients = $this->faker->randomElements($ingredients, mt_rand(2, 5));
            foreach ($usedIngredients as $ingredient) {
                $recipe->addIngredient($ingredient);
        
            }
            $manager->persist($recipe);
        }
        $manager->flush();
    }
}
