<?php


namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractController
{

    private $articleRepository;
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }
    /**
     * @param string $slug The slugger
     * @Route("/blog/category/slug/{slug}", requirements={ "slug" = "[0-9a-z-]+" }, name="blog_show")
     * @return Response A response instance
     */
    public function show($slug = '1'): Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find an article in article\'s table.');
        }

        $article = $this->articleRepository->findOneBy(['category' => $slug]);

        if (!$article) {
            throw $this->createNotFoundException('No article with ' . $slug . ' title, found in article\'s table.');
        }

        return $this->render('Blog/show.html.twig', ['article' => $article, 'slug' => $slug]);
    }
}

