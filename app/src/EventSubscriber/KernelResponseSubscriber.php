<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class KernelResponseSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'makeJsonResponse'
        ];
    }

    public function makeJsonResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        $content = $response->getContent();

        $response->headers->set('Content-Type', 'application/json');

        $decodedContent = json_decode($content, true);
        $jsonContent = [
            'success' => $response->isSuccessful(),
            'content' => json_last_error() === JSON_ERROR_NONE ? $decodedContent : $content
        ];

        $response->setContent(json_encode($jsonContent));
        $event->setResponse($response);
    }
}
