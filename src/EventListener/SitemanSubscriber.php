<?php
/**
 * Created by PhpStorm.
 * User: STOYO
 * Date: 30.9.2018 г.
 * Time: 16:56 ч.
 */

namespace App\EventListener;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Presta\SitemapBundle\Sitemap\Url\GoogleImage;
use Presta\SitemapBundle\Sitemap\Url\GoogleImageUrlDecorator;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
     * @param UrlGeneratorInterface $urlGenerator
     * @param EntityManagerInterface $doctrine
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, $entityManager, $languagesForRoutes)
    {
        $this->urlGenerator = $urlGenerator;
        $this->entityManager = $entityManager;
        $this->languagesForRoutes = $languagesForRoutes;
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
        $this->registerArticlesUrls($event->getUrlContainer());
    }

    /**
     * @param UrlContainerInterface $urls
     */
    public function registerArticlesUrls(UrlContainerInterface $urls): void
    {
        $articles = $this->entityManager->getRepository(Article::class)->findAll();


        foreach ($articles as $article) {

            foreach ($this->languagesForRoutes as $language)
            {
                $urlToIndex =
                    new UrlConcrete($this->urlGenerator->generate('show'.'.'.$language, ['name' => $article->getName()],UrlGeneratorInterface::ABSOLUTE_URL),
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
}
