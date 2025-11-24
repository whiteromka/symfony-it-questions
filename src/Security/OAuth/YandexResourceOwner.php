<?php

namespace App\Security\OAuth;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class YandexResourceOwner implements ResourceOwnerInterface
{
    private array $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function getId()
    {
        return $this->response['id'] ?? null;
    }

    public function getEmail()
    {
        // Яндекс возвращает email в default_email
        return $this->response['default_email'] ?? $this->response['emails'][0] ?? null;
    }

    public function getFirstName()
    {
        return $this->response['first_name'] ?? null;
    }

    public function getLastName()
    {
        return $this->response['last_name'] ?? null;
    }

    public function getLogin()
    {
        return $this->response['login'] ?? null;
    }

    public function getRealName()
    {
        return $this->response['real_name'] ?? null;
    }

    public function getDisplayName()
    {
        return $this->response['display_name'] ?? null;
    }

    public function toArray()
    {
        return $this->response;
    }
}
