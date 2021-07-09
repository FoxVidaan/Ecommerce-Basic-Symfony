<?php

namespace App\EventDispatcher;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Http\Firewall\AbstractListener;

class PrenomSubscriber implements EventSubscriberInterface
{
    public function addPrenomToAttributes(RequestEvent $event) {
        $event->getRequest()->attributes->add(['prenom' => 'Lior']);
    }

    public function test1() {
//        dump("test1");
    }

    public function test2() {
//        dump("test2");
    }

    public static function getSubscribedEvents()
    {
        return [
          'kernel.request' => 'addPrenomToAttributes',
          'kernel.controller' => 'test1',
          'kernel.response' => 'test2'
        ];
    }
}