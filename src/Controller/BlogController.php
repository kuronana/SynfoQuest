<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class BlogController extends AbstractController
{
    /**
     * @route("/blog/page/{page}", name = "blog_list", requirements = { "page" = "\d+"})
     */

    public function list($page = 1)
    {
        return $this->render("index.html.twig", ['page' => $page]);
    }


    /**
     * @route("/blog/{slug}", name = "blog_show", requirements = { "slug" = "[a-z0-9-]+" })
     */

    public function show($slug = "article-sans-titre")
    {
        $slug = str_replace("-", " ", $slug);
        $slug = ucwords($slug);

        return $this->render("blog.html.twig", ["slug" => $slug]);

    }
}