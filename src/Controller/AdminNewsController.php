<?php
/**
 * Created by PhpStorm.
 * User: STOYO
 * Date: 29.9.2018 г.
 * Time: 15:45
 */

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AdminNewsController extends AbstractController
{

    /**
     * @Route("/admin/news/new")
     * @return Response
     */
    public function newNews($em)
    {
        $article = new Article();
        $article->setName("Novina 1")
            ->setAuthor("Stoyan Kalinov")
            ->setCategory("Bulgaria")
            ->setContent("
            Laboris beef ribs fatback fugiat eiusmod jowl kielbasa alcatra dolore velit ea ball tip. Pariatur
laboris sunt venison, et laborum dolore minim non meatball. Shankle eu flank aliqua shoulder,
capicola biltong frankfurter boudin cupim officia. Exercitation fugiat consectetur ham. Adipisicing
picanha shank et filet mignon pork belly ut ullamco. Irure velit turducken ground round doner incididunt
occaecat lorem meatball prosciutto quis strip steak.
            ")
            ->setImg("img3.jpg");

        $em->persist($article);
        $em->flush();

        return new Response(sprintf("Novina number #%d s avtor %s", $article->getId(), $article->getAuthor()));
    }
}