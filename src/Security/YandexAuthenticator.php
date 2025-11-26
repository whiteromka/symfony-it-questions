<?php

namespace App\Security;

use App\Entity\User;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

/** Авторизация через Yandex */
class YandexAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserService $userService,
        private readonly RouterInterface        $router
    ) {}

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'connect_yandex_authenticate';
    }

    public function authenticate(Request $request): Passport
    {
        $yandexData = $request->getSession()->get('yandex_user_data');

        if (!$yandexData) {
            throw new AuthenticationException('No Yandex user data found');
        }

        $resourceOwner = $yandexData['resourceOwner'];

        $email = $resourceOwner['default_email'] ?? null;
        $yandexId = $resourceOwner['id'] ?? null;
        $firstName = $resourceOwner['first_name'] ?? null;
        $lastName = $resourceOwner['last_name'] ?? null;

        return new SelfValidatingPassport(
            new UserBadge($email, function() use ($email, $yandexId, $firstName, $lastName) {
                if (!$email || !$yandexId) {
                    throw new AuthenticationException('Yandex authentication failed');
                }

                // Ищем пользователя по Yandex ID
                $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['yandexId' => $yandexId]);
                if ($existingUser) {
                    return $existingUser;
                }

                // Ищем пользователя по email
                $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

                if ($user) {
                    $user->setYandexId($yandexId);
                } else {
                    // Создаем нового пользователя
                    $user = $this->userService->newUser(
                        email: $email,
                        name: $firstName,
                        lastName: $lastName,
                    );
                    $user->setYandexId($yandexId);
                }
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                return $user;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $request->getSession()->remove('yandex_user_data');
        $request->getSession()->remove('oauth2state');

        return new RedirectResponse($this->router->generate('app_home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $request->getSession()->remove('yandex_user_data');
        $request->getSession()->remove('oauth2state');

        return new Response($exception->getMessage(), Response::HTTP_FORBIDDEN);
    }
}