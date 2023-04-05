<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Exception\BookingOverlapException;
use App\Exception\NotAuthorizedException;
use App\Exception\OutOfIndexPagerException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Centralize the exception handling on the application
 *
 * Handles:
 * - @class BookingOverlapException, for overlapped bookings
 *
 */
class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        if ($exception instanceof BookingOverlapException) {
            $request->getSession()->getFlashBag()->add('error', 'The booking overlaps with another booking, please check the given dates');
            $event->setResponse(new RedirectResponse('/booking/new'));
        }

        if ($exception instanceof NotAuthorizedException) {
            $request->getSession()->getFlashBag()->add('error', 'You are not authorized to perform this action');
            $event->setResponse(new RedirectResponse('/'));
        }

        if ($exception instanceof OutOfIndexPagerException) {
            // TODO: When last bookings of user with past bookings is deleted, the pager is out of index but it tries to
            // Redirect to an unexistant page
            $event->setResponse(new RedirectResponse($request->getPathInfo()));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
