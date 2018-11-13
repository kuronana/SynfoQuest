<?php


namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends AbstractController
{
    private $articleRepository;
    private $categoryRepository;

    public function __construct(ArticleRepository $articleRepository, CategoryRepository $categoryRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->categoryRepository = $categoryRepository;
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
    public function show($id = '1') : Response
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
     * @Route("/category/remove/{id}", name="deleteCategory")
     */
    public function deleteCategory($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository(Category::class)->find($id);
        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('category');
    }
}