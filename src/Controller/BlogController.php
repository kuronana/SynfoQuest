<?php


namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends AbstractController
{
    private $articleRepository;
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * Show all row from article's entity
     *
     * @Route("/", name="blog_index")
     * @return Response A response instance
     */
    public function index() : Response
    {
        $articles = $this->articleRepository->findAll();

        if (!$articles)
        {
            throw $this->createNotFoundException( 'No article found in article\'s table.');
        }

        return $this->render('index.html.twig', ['articles' => $articles]);
    }


    /**
     * @param string $slug The slugger
     * @Route("/blog/{slug}", requirements={ "slug" = "[0-9a-z-]+" }, name="blog_show")
     * @return Response A response instance
     */
    public function show($slug = 'test1') : Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find an article in article\'s table.');
        }

        $slug = ucwords(str_replace("-", " ", $slug));

        $article = $this->articleRepository->findOneBy(['title' => $slug]);

        if (!$article) {
            throw $this->createNotFoundException('No article with ' . $slug . ' title, found in article\'s table.');
        }

            return $this->render('Blog/show.html.twig', ['article' => $article, 'slug' => $slug]);
    }


    /**
     * @param $category
     * @Route("/blog/category/{category}", name="blog_show_category")
     * @return Response A response instance
     */
    public function showByCategory($category)
    {

        if (!$category)
        {
            throw $this->createNotFoundException('No slug has been sent to find an article in article\'s table.');
        }

        $categ = $this->articleRepository->findBycategory($category);
        $oneCateg = $this->articleRepository->findOneBycategory($category);

        return $this->render('Blog/category.html.twig', ['categ' => $categ, 'onecateg' => $oneCateg]);

    }
}