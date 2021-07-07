<?php

namespace Oka\Notifier\ClientBundle\EventListener;

use Oka\Notifier\ClientBundle\Notifier;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\TerminateEvent;

/**
 * @author Cedrick Oka Baidai <okacedrick@gmail.com>
 */
class RequestSubscriber implements EventSubscriberInterface
{
    private $notifier;

    public function __construct(Notifier $notifier)
    {
        $this->notifier = $notifier;
    }

    public function onKernelTerminate(TerminateEvent $event)
    {
        if (false === $event->isMasterRequest()) {
            return;
        }

        $this->notifier->flush();
    }

    public function onConsoleTerminate(ConsoleTerminateEvent $event)
    {
        if (0 !== $event->getExitCode()) {
            return;
        }

        $this->notifier->flush();
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::TERMINATE => 'onKernelTerminate',
            ConsoleEvents::TERMINATE => 'onConsoleTerminate'
        ];
    }
}
