<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\ApiRequest;
use App\Entity\ApiResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiLoggerSubscriber implements EventSubscriberInterface
{
    private ?ApiRequest $currentApiRequest = null;

    public function __construct(private EntityManagerInterface $entityManager) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
            KernelEvents::TERMINATE => 'onKernelTerminate',
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if (!$this->entityManager->isOpen()) {
            return;
        }

        $request = $event->getRequest();
        $this->currentApiRequest = new ApiRequest(
            $request->getMethod(),
            $request->getRequestUri(),
            $request->headers->all(),
            $request->getContent(),
            $request->getClientIp(),
            $request->headers->get('User-Agent'),
        );

        $this->entityManager->persist($this->currentApiRequest);
        $this->entityManager->flush();
    }

    public function onKernelTerminate(TerminateEvent $event)
    {
        if (!$this->entityManager->isOpen()) {
            return;
        }

        $response = $event->getResponse();
        $apiResponse = new ApiResponse(
            $this->currentApiRequest,
            $response->getStatusCode(),
            $response->headers->all(),
            $response->getContent(),
        );

        $this->entityManager->persist($apiResponse);
        $this->entityManager->flush();
    }
}
