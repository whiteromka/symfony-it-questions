<?php

namespace App\EventSubscriber;

use App\Validator\ValidateCsrfToken;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class CsrfTokenSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly CsrfTokenManagerInterface $csrfTokenManager
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    /**
     * @throws \ReflectionException
     */
    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();
        if (!is_array($controller)) {
            return;
        }

        [$controllerObject, $methodName] = $controller;

        $reflectionMethod = new \ReflectionMethod($controllerObject, $methodName);
        // Тут всегда будет только ValidateCsrfToken аттрибут или если на экшене нет аттрибута то []
        $attributesValidateCsrf = $reflectionMethod->getAttributes(ValidateCsrfToken::class);
        if (empty($attributesValidateCsrf)) {
            return;
        }
        /** @var ValidateCsrfToken $attribute */
        $attribute = $attributesValidateCsrf[0]->newInstance();

        // Ищем токен в данных запроса или заголовках
        $request = $event->getRequest();
        $token = $request->request->get($attribute->paramName);
        if (!$token) {
            $token = $request->headers->get('X-CSRF-TOKEN');
        }

        if (!$token || !$this->csrfTokenManager->isTokenValid(new CsrfToken($attribute->tokenId, $token))) {
            // Для JSON API возвращаем JSON response
            if ($request->getRequestFormat() === 'json') {
                $response = new JsonResponse([
                    'success' => false,
                    'errors' => ['Неверный CSRF токен'],
                    'data' => []
                ], 419);
                $event->setController(fn() => $response);
            }
        }
    }
}
