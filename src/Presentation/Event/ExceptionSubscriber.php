<?php

declare(strict_types=1);

namespace App\Presentation\Event;

use App\Presentation\Controller\Request\Exception\RequestValidationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final readonly class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => [
            'onKernelException',
        ]];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        match (true) {
            $exception instanceof RequestValidationException => $event->setResponse(new JsonResponse(
                data: [
                    'Request Validation Errors' => $this->normalizeConstraintViolationList($exception->getViolations()),
                ],
                status: Response::HTTP_BAD_REQUEST,
            )),
            $exception instanceof \DomainException => $event->setResponse(new JsonResponse(
                data: [
                    'Generic Validation Errors' => [$exception->getMessage()],
                ],
                status: Response::HTTP_UNPROCESSABLE_ENTITY,
            )),
            $exception instanceof NotFoundHttpException => $this->setGenericResponse(
                $event,
                'Resource Not Found',
                Response::HTTP_NOT_FOUND,
            ),
            default => $this->setGenericResponse(
                $event,
                'Unexpected Error',
                Response::HTTP_INTERNAL_SERVER_ERROR,
            ),
        };
    }

    private function setGenericResponse(ExceptionEvent $event, string $message, int $httpCode): void
    {
        $event->setResponse(
            new JsonResponse(
                data: ['Generic Error' => $message],
                status: $httpCode,
            )
        );
    }

    /**
     * @return array<string, string>
     */
    private function normalizeConstraintViolationList(ConstraintViolationListInterface $list): array
    {
        $normalized = [];

        foreach ($list as $violation) {
            $normalized[$violation->getPropertyPath()] = (string) $violation->getMessage();
        }

        return $normalized;
    }
}
