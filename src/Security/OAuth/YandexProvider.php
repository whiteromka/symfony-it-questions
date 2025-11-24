<?php

namespace App\Security\OAuth;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class YandexProvider extends AbstractProvider
{
    use BearerAuthorizationTrait;

    public function getBaseAuthorizationUrl()
    {
        return 'https://oauth.yandex.ru/authorize';
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return 'https://oauth.yandex.ru/token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return 'https://login.yandex.ru/info?format=json';
    }

    protected function getDefaultScopes()
    {
        return '';
    }

    protected function getScopeSeparator()
    {
        return ' ';
    }

    protected function getAuthorizationParameters(array $options)
    {
        $params = parent::getAuthorizationParameters($options);
        $params['response_type'] = 'code';

        return $params;
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (isset($data['error'])) {
            $code = $response->getStatusCode();
            $error = $data['error'];
            $message = $data['message'] ?? $response->getReasonPhrase();
            throw new IdentityProviderException($message, $code, $data);
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new YandexResourceOwner($response);
    }
}
