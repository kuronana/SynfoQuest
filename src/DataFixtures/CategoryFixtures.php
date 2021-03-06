<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    private $categories = [
        'Animes',
        'Mangas',
        'Series',
        'Jeux',
        'Plaisir'
    ];

    public function load(ObjectManager $manager)
    {



        foreach ($this->categories as $key=> $categoryName){
        $category = new Category();
        $category->setName($categoryName);
        $manager->persist($category);
        $this->addReference('category_' . $key, $category);
    }
    $manager->flush();
    }
}