<?php
//
//namespace App\EventSubscriber;
//
//use Symfony\Component\EventDispatcher\EventSubscriberInterface;
//use Symfony\Component\HttpFoundation\JsonResponse;
//use Symfony\Component\HttpKernel\Event\ExceptionEvent;
//use Symfony\Component\HttpKernel\Exception\HttpException;
//use Symfony\Component\HttpKernel\KernelEvents;
//use Symfony\Component\Validator\Exception\ValidationFailedException;
//use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
//use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
//
//class ApiExceptionSubscriber implements EventSubscriberInterface
//{
//    public static function getSubscribedEvents(): array
//    {
//        return [
//            KernelEvents::EXCEPTION => 'onKernelException',
//        ];
//    }
//
//    public function onKernelException(ExceptionEvent $event): void
//    {
//        $exception = $event->getThrowable();
//        $request = $event->getRequest();
//
//        // Проверяем, что запрос к API
//        if (strpos($request->getPathInfo(), '/api/') !== 0) {
//            return;
//        }
//
//        $statusCode = $exception instanceof HttpException
//            ? $exception->getStatusCode()
//            : JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
//
//        $data = [
//            'success' => false,
//            'error' => $exception->getMessage(),
//        ];
//
//        // Обработка ошибок валидации через #[MapRequestPayload]
//        if ($exception instanceof BadRequestHttpException && $exception->getPrevious() instanceof ValidationFailedException) {
//            $validationException = $exception->getPrevious();
//            $data['errors'] = [];
//            foreach ($validationException->getViolations() as $violation) {
//                $data['errors'][$violation->getPropertyPath()] = $violation->getMessage();
//            }
//            $statusCode = JsonResponse::HTTP_BAD_REQUEST;
//        }
//        // Обработка ошибок десериализации
//        elseif ($exception instanceof BadRequestHttpException && $exception->getPrevious() instanceof NotNormalizableValueException) {
//            $deserializationException = $exception->getPrevious();
//            $data['errors'] = [
//                    $deserializationException->getPath() ?? 'input' => $deserializationException->getMessage()
//            ];
//            $statusCode = JsonResponse::HTTP_BAD_REQUEST;
//        }
//
//        $event->setResponse(new JsonResponse($data, $statusCode));
//    }
//}