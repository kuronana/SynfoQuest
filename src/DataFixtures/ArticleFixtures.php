<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 50 ; $i++)
        {
            for ($a = 0; $a < 5; $a++) {
                $article = new Article();
                $faker = Faker\Factory::create('ja_JP');

                $article->setTitle($faker->name);
                $article->setContent($faker->text);

                $manager->persist($article);
                $article->setCategory($this->getReference('category_' . $a));
            }
        }
        $manager->flush();
    }
}