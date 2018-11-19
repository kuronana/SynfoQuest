<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Form\CategoryType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use App\Service\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends AbstractController
{
    private $articleRepository;
    private $categoryRepository;
    private $tagRepository;

    public function __construct(ArticleRepository $articleRepository, CategoryRepository $categoryRepository, TagRepository $tagRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
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
     * @Route("/blog/{id}", requirements={ "slug" = "[0-9a-z-]+" }, name="blog_show")
     * @return Response A response instance
     */
    public function show($id) : Response
    {
        if (!$id) {
            throw $this
                ->createNotFoundException('No slug has been sent to find an article in article\'s table.');
        }

        $slug = ucwords(str_replace("-", " ", $id));

        $article = $this->articleRepository->findOneBy(['id' => $slug]);

        if (!$article) {
            throw $this->createNotFoundException('No article with ' . $slug . ' title, found in article\'s table.');
        }

            return $this->render('Blog/show.html.twig', ['article' => $article, 'slug' => $slug]);
    }


    /**
     * @param $category
     * @Route("/blog/category/{slug}", name="blog_show_category")
     * @return Response A response instance
     */
    public function showByCategory($slug = '1')
    {

        if (!$slug)
        {
            throw $this->createNotFoundException('No slug has been sent to find an article in article\'s table.');
        }

        $categ = $this->articleRepository->findBycategory($slug);
        $oneCateg = $this->articleRepository->findOneBy(['category' => $slug]);

        return $this->render('Blog/category.html.twig', ['categ' => $categ, 'onecateg' => $oneCateg]);

    }


    /**
     * @return Response
     * @Route("/category", name="category")
     */
    public function addCategory(Request $request)
    {
        $categories = $this->categoryRepository->findAll();

        $form = $this->createForm(CategoryType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $dataCateg = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($dataCateg);
            $em->flush();
            return $this->redirectToRoute('category');
        }

        return $this->render('Blog/addCategory.html.twig', ['categories' => $categories, 'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @Route("/article", name="addArticle")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addArticle(Request $request, Slugify $slugify) :Response
    {
        $articles = $this->articleRepository->findAll();

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $article->setSlug($slugify->generate($article->getTitle()));
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('addArticle');
        }

        return $this->render('Blog/addArticle.html.twig', ['articles' => $articles, 'form' => $form->createView()]);
    }

    /**
     * @Route("/category/remove/{id}", name="deleteCategory")
     */
    public function deleteCategory($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $this->categoryRepository->find($id);
        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('category');
    }

    /**
     * @return Response
     * @Route("/tag/{name}", name="blog_tag")
     */
    public function showTag($name = 'otaku')
    {
        $tag = $this->tagRepository->findOneByname($name);
        $tags = $this->tagRepository->findAll();

        return $this->render('Blog/tags.html.twig', ['tag' => $tag, 'tags' => $tags]);
    }

    /**
     * @param $title
     * @return Response
     * @Route("/article/{id}", name="blog_article")
     */
    public function showArticle($id)
    {
        $article = $this->articleRepository->find($id);

        return $this->render('Blog/article.html.twig', ['article' => $article]);
    }
}