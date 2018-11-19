<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        for ($a = 0; $a < 4; $a ++) {
            for ($i = 1; $i <= 50; $i++) {
                $faker = Factory::create('fr_FR');
                $article = new Article();
                $slugify = new Slugify();
                $article->setTitle(strtolower($faker->name));
                $article->setContent(strtolower($faker->text));
                $article->setSlug($slugify->generate($article->getTitle()));
                $article->setCategory($this->getReference('categories_' . $a));

                $manager->persist($article);
            }
        }
        $manager->flush();
    }
}