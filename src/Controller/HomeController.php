<?php


namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{
    private $Repository;
    private $repository;

    public function __construct(ArticleRepository $Repository, CategoryRepository $repository)
    {
        $this->Repository = $Repository;
        $this->repository = $repository;
    }

    /**
     * @Route("/")
     */
        public function index()
        {
            $articles = $this->Repository->findAll();
            $categories = $this->repository->findAll();



            return $this->render('home.html.twig', ['articles' => $articles, 'categories' => $categories]);
        }
}