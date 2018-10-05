<?php
/**
 * Created by PhpStorm.
 * User: stoyan.kalinov
 * Date: 5.10.2018 г.
 * Time: 16:30
 */

namespace App\Service;


use App\Service\ServiceInterface\RouteSortInteface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class RouteSort implements RouteSortInteface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * RouteSort constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getRoutesForSitemap(): array
    {
        $routeObjects = $this->router->getRouteCollection()->all();
        $routes = [];
        foreach ($routeObjects as $routeName => $routeObj)
        {
            if(strpos($routeName, 'admin') === false && substr($routeName, 0, 1) !== '_')
            {
               $routes[$routeName] = $routeObj->getPath();
            }
        }

        return $routes;
    }

    /**
     * @return array
     */
    public function getAllStaticRoutesForSitemap(): array
    {
        $routeObjects = $this->getRoutesForSitemap();
        $routes = [];

        foreach($routeObjects as $routeName => $routePath)
        {
            if(strpos($routePath, '{') === false)
            {
                $routes[$routeName] = $routePath;
            }
        }

        return $routes;
    }

}