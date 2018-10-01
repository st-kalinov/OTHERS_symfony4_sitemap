<?php
/**
 * Created by PhpStorm.
 * User: STOYO
 * Date: 30.9.2018 г.
 * Time: 14:37 ч.
 */

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="news",
     *     options={"sitemap" = true})
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index($entityManager)
    {
        $repository = $entityManager->getRepository(Article::class);

        $articles = $repository->findAll();

        return $this->render('homepage.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route({
     *     "en": "/news/{name}",
     *     "bg": "/novini/{name}"
     * }, name="show")
     *
     * @param $name
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show($name, $entityManager)
    {
        $name = str_replace('-', ' ', $name);
        $repository = $entityManager->getRepository(Article::class);
        $article = $repository->findOneBy(['name' => $name]);

        return $this->render('news.html.twig', ['article' => $article]);
    }
}