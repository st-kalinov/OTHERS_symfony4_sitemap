<?php
/**
 * Created by PhpStorm.
 * User: stoyan.kalinov
 * Date: 1.10.2018 г.
 * Time: 10:07
 */

namespace App\EventListener;


use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;

class TestSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(LoggerInterface $logger, RouterInterface $router)
    {
        $this->logger = $logger;
        $this->router = $router;
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
           // constant that means kernel.request
            KernelEvents::REQUEST => 'test'
       ];
    }

    public function test(GetResponseEvent $event)
    {

        $request = $event->getRequest();
        $this->logger->info($request->getLocale());
        $this->logger->info($request->getDefaultLocale());

    }

}