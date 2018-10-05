<?php

namespace App\Controller;

use App\Service\ServiceInterface\RouteSortInteface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class TestController extends AbstractController
{

    /**
     * @Route(
     *  "/test",
     *     defaults={"repositories": {"ArticleRepository","ArticleRepository2"}},
     *     name="test"
     *
     *  )
     * @param RouterInterface $router
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(RouterInterface $router, Request $request, RouteSortInteface $routeSort)
    {

        $routes = $routeSort->getAllStaticRoutesForSitemap();
       //foreach ($routeObjects as $routeName => $routeObj)
       //{
       //    if(strpos($routeName, 'admin') === false && substr($routeName, 0, 1) !== '_')
       //    {
       //        if(strpos($routeObj->getPath(), '{') === false)
       //        {
       //            $routes[$routeName] = $routeObj->getPath();
       //        }
       //    }
       //}

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    /**
     * @Route("/test1", name="test1")
     */
    public function index1()
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    /**
     * @Route("/test2", name="test2")
     */
    public function index2()
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    /**
     * @Route("/test3", name="test3")
     */
    public function index3()
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
    /**
     * @Route("/test10", name="test10")
     */
    public function index10()
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
