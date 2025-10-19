<?php

namespace App\Controller;


use App\Dto\Auth\RegistrationDto;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $registrationDto = new RegistrationDto();
        $form = $this->createForm(RegistrationFormType::class, $registrationDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Создаем пользователя из DTO
            $user = new User();
            $user->setName($registrationDto->name);
            $user->setLastName($registrationDto->lastName);
            $user->setEmail($registrationDto->email);
            $user->setPassword(
                $passwordHasher->hashPassword($user, $registrationDto->plainPassword)
            );
            $user->setRoles(['ROLE_USER']);
            $user->setStatus(1); // активный пользователь

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Регистрация прошла успешно. Теперь можно войти!');
            return $this->redirectToRoute('app_login');
        }
        $allErrors = [];
        foreach ($form->getErrors(true) as $error) {
            $allErrors[$error->getOrigin()->getName()] = $error->getMessage();
        }
        dump($allErrors);


        return $this->render('auth/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Если пользователь уже авторизован, перенаправляем его
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // Получаем ошибку входа, если есть
        $error = $authenticationUtils->getLastAuthenticationError();
        // Последнее имя пользователя, введенное пользователем
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('auth/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Этот метод может быть пустым - он будет перехвачен системой безопасности Symfony
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}