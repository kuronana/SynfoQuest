<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog/{slug}", requirements={ "slug" = "[0-9a-z-]+" }, name="blog_list")
     */

    public function show($slug = 'article-sans-nom')
    {
        $slug = ucwords(str_replace("-", " ", $slug));
        return $this->render('blog.html.twig', ['slug' => $slug]);
    }
}