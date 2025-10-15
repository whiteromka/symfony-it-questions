<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Throwable;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    /**
     * Метод вызывается когда в приложении возникает любое неперехваченное исключение.
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        // Обрабатываем только API исключения
        if (str_starts_with($request->getPathInfo(), '/api/')) {
            $response = $this->createApiErrorResponse($exception);
            $event->setResponse($response);
        }
    }

    /**
     * Формирует ответ в случаи возникновения ошибки в /api/...
     */
    private function createApiErrorResponse(Throwable $exception): JsonResponse
    {
        $statusCode = 500;
        $errors = [];

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getStatusCode();
            $previousException = $exception->getPrevious();

            // Обрабатываем ошибки валидации
            if ($previousException instanceof ValidationFailedException) {
                $violations = $previousException->getViolations();
                foreach ($violations as $violation) {
                    $propertyPath = $violation->getPropertyPath();
                    $errors[$propertyPath] = $violation->getMessage();
                }
            }
            // (4XX, 5XX и т.д.), которые НЕ связаны с валидацией
            else {
                $baseError = '';
                if ($this->isError4XX($statusCode)) {
                    $baseError = "Ошибка  $statusCode : ";
                } else if ($this->isError5XX($statusCode)) {
                    $baseError = "Ошибка сервера  $statusCode : ";
                }
                $errors['message'] = $baseError . $exception->getMessage();
            }
        } else {
            // Внутренняя ошибка сервера
            $errors['message'] = ' Внутренняя ошибка сервера: ' . $exception->getMessage();;
        }

        return new JsonResponse([
            'success' => false,
            'errors' => $errors,
            'data' => null,
        ], $statusCode);
    }

    private function isError4XX(string $statusCode): bool
    {
        return ($statusCode && ($statusCode)[0] == 4);
    }

    private function isError5XX(string $statusCode): bool
    {
        return ($statusCode && ($statusCode)[0] == 5);
    }
}
