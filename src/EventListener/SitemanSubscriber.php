<?php
/**
 * Created by PhpStorm.
 * User: STOYO
 * Date: 30.9.2018 г.
 * Time: 16:56 ч.
 */

namespace App\EventListener;

use App\Entity\Article;
use App\Service\ServiceInterface\RouteSortInteface;
use Doctrine\ORM\EntityManagerInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Exception\Exception;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Presta\SitemapBundle\Sitemap\Url\GoogleImage;
use Presta\SitemapBundle\Sitemap\Url\GoogleImageUrlDecorator;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class SitemanSubscriber implements EventSubscriberInterface
{

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    private $languagesForRoutes;
    /**
     * @var RouterInterface
     */
    private $routeSorter;


    /**
     * SitemanSubscriber constructor.
     * @param array $languages
     * @param UrlGeneratorInterface $urlGenerator
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(array $languages, UrlGeneratorInterface $urlGenerator, EntityManagerInterface $entityManager, RouteSortInteface $routeSorter)
    {
        $this->urlGenerator = $urlGenerator;
        $this->entityManager = $entityManager;
        $this->languagesForRoutes = $languages;
        $this->routeSorter = $routeSorter;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            SitemapPopulateEvent::ON_SITEMAP_POPULATE => 'populate',
        ];
    }


    /**
     * @param SitemapPopulateEvent $event
     */
    public function populate(SitemapPopulateEvent $event): void
    {
        $this->registerCategories($event->getUrlContainer());
        $this->registerArticle($event->getUrlContainer());
        $this->registerStaticRoutes($event->getUrlContainer());
    }

    /**
     * @param UrlContainerInterface $urls
     *
     * @example url = http://127.0.0.1:8000/{_locale}/{news|novini}/{category}
     */
    public function registerCategories(UrlContainerInterface $urls): void
    {
        $categories = $this->entityManager->getRepository(Article::class)->findAllCategories();

        foreach ($categories as $category)
        {
            foreach ($this->languagesForRoutes as $language)
            {
                $urlToIndex =
                    new UrlConcrete($this->urlGenerator->generate('showAllbyCategory'.'.'.$language, ['category' => $category['category']],
                        UrlGeneratorInterface::ABSOLUTE_URL),

                        new \DateTime(),
                        UrlConcrete::CHANGEFREQ_HOURLY,
                        1);
                $urls->addUrl(
                    $urlToIndex,
                    $language
                );
            }

           // $urlToIndex = new GoogleImageUrlDecorator($urlToIndex);
           // $urlToIndex->addImage(
           //     new GoogleImage(
           //         $this->urlGenerator->generate('news',[],UrlGeneratorInterface::ABSOLUTE_URL).
           //         $article->getImg()
           //     )
           // );
        }
    }

    public function registerStaticRoutes(UrlContainerInterface $urls): void
    {
        $routes = $this->routeSorter->getAllStaticRoutesForSitemap();
        if(count($routes) === 0)
        {
            throw new Exception("No static routes available");
        }

        foreach ($routes as $name => $path)
        {
            $urlToIndex = new UrlConcrete($this->urlGenerator->generate($name, [], UrlGeneratorInterface::ABSOLUTE_URL),
                new \DateTime(),
                UrlConcrete::CHANGEFREQ_HOURLY,
                1);

            $urls->addUrl(
                $urlToIndex,
                substr($name, -2)
            );
        }
    }

    /**
     * @param UrlContainerInterface $urls
     *
     * @example url = http://127.0.0.1:8000/{_locale}/{news|novini}/{category}/{article_N}
     */
    public function registerArticle(UrlContainerInterface $urls): void
   {
       $articles = $this->entityManager->getRepository(Article::class)->findAll();

       foreach ($articles as $article)
       {
           foreach ($this->languagesForRoutes as $language)
           {
               $urlToIndex =
                   new UrlConcrete($this->urlGenerator->generate('show'.'.'.$language,
                       ['category' => $article->getCategory(), 'name' => $article->getName()], UrlGeneratorInterface::ABSOLUTE_URL),
               new \DateTime(),
               UrlConcrete::CHANGEFREQ_HOURLY,
               1);

               $urls->addUrl(
                   $urlToIndex,
                   $language
               );
           }
       }
   }
}
