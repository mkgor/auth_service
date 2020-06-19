<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class KernelResponse
 *
 * @package App\EventListener
 */
class KernelResponse
{
    /**
     * @param ResponseEvent $event
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function onKernelResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();

        $data = json_decode($response->getContent());

        $formattedContent = [];

        $formattedContent['code'] = $response->getStatusCode();
        $formattedContent['data'] = $data;

        return $response->setContent(json_encode($formattedContent));
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }
}