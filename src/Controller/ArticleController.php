<?php
/**
 * Created by PhpStorm.
 * User: STOYO
 * Date: 30.9.2018 г.
 * Time: 14:37 ч.
 */

namespace App\Controller;

use App\Entity\Article;
use App\Service\ServiceInterface\EncodePasswordInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="showAll",
     *     options={"sitemap" = true})
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAll($entityManager)
    {
        $articles = $entityManager
            ->getRepository(Article::class)
            ->findAll();

        return $this->render('homepage.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route({
     *   "en": "/news/{category}",
     *   "bg": "/novini/{category}"
     *    },
     *      name="showAllbyCategory")
     *
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
   public function showAllbyCategory($category, $entityManager)
   {
       $articles  = $entityManager
           ->getRepository(Article::class)
           ->findBy(['category' => $category]);

        return $this->render('articlesByCategory.html.twig', ['articles' => $articles]);
   }

    /**
     * @Route({
     *     "en": "/news/{category}/{name}",
     *     "bg": "/novini/{category}/{name}"
     * }, name="show")
     * @param EntityManagerInterface $entityManager
     * @param $name
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show($name, $category, $entityManager)
    {
        $name = str_replace('-', ' ', $name);

        $article = $entityManager
            ->getRepository(Article::class)
            ->findOneBy(['name' => $name, 'category' => $category]);

        return $this->render('article.html.twig', ['article' => $article]);
    }

   // /**
   //  * @Route("/test", name="test")
   //  * @param EntityManagerInterface $entityManager
   //  */
   // public function test($entityManager)
   // {
   //     $categories = $entityManager
   //         ->getRepository(Article::class)
   //         ->findAllCategories();
//
   // }

    /**
     * @Route("/testEncode", name="test_encode")
     * @param EncodePasswordInterface $encodePasswords
     * @param RouterInterface $router
     * @return RedirectResponse
     */
    public function testEncode(EncodePasswordInterface $encodePasswords, RouterInterface $router)
    {
        $encodePasswords->setEncodedPasswords();

        return new RedirectResponse($router->generate('showAll'));

    }
}