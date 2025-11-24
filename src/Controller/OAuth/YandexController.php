<?php

namespace App\Controller\OAuth;

use App\Security\OAuth\YandexProvider;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class YandexController extends AbstractController
{
    private YandexProvider $yandexProvider;

    public function __construct(YandexProvider $yandexProvider)
    {
        $this->yandexProvider = $yandexProvider;
    }

    #[Route('/connect/yandex', name: 'connect_yandex_start')]
    public function connectAction(Request $request): RedirectResponse
    {
        $authorizationUrl = $this->yandexProvider->getAuthorizationUrl();
        // Сохраняем state в сессию для проверки
        $request->getSession()->set('oauth2state', $this->yandexProvider->getState());

        return new RedirectResponse($authorizationUrl);
    }

    #[Route('/connect/yandex/check', name: 'connect_yandex_check')]
    public function connectCheckAction(Request $request)
    {
        $allParams = $request->query->all();

        // Проверяем state для защиты от CSRF
        $state = $request->get('state');
        $storedState = $request->getSession()->get('oauth2state');

        if (empty($state) || $state !== $storedState) {
            $request->getSession()->remove('oauth2state');
            throw new \RuntimeException('Invalid state');
        }

        $code = $request->get('code');
        // Если code не найден, проверяем другие возможные параметры
        if (!$code) {
            $code = $request->get('oauth_token') ?? $request->get('authorization_code');
        }
        if (!$code) {
            throw new \RuntimeException('Authorization code not received from Yandex. Parameters: ' . print_r($allParams, true));
        }

        // Получаем access token
        try {
            $accessToken = $this->yandexProvider->getAccessToken('authorization_code', [
                'code' => $code
            ]);

            // Получаем данные пользователя
            $resourceOwner = $this->yandexProvider->getResourceOwner($accessToken);

            // Сохраняем в сессию для аутентификатора
            $request->getSession()->set('yandex_user_data', [
                'resourceOwner' => $resourceOwner->toArray(), // тут есть все данные о пользователе
                'accessToken' => $accessToken->getToken() // тут токен
            ]);

            return $this->redirectToRoute('connect_yandex_authenticate');

        } catch (Exception $e) {
            throw new \RuntimeException('Failed to get access token: ' . $e->getMessage());
        }
    }

    #[Route('/connect/yandex/authenticate', name: 'connect_yandex_authenticate')]
    public function authenticateAction()
    {
        // тут автоматом вызовется App/Security/YandexAuthenticator
        return $this->redirectToRoute('app_home');
    }
}
