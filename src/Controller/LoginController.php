<?php

namespace App\Controller;

use App\Entity\UserEntity;
use App\Form\RegisterFormType;
use App\Form\LoginFormType;
use App\Shortener\Interfaces\User\InterfaceUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{

    #[Route('/register_form', name: 'register_form')]
    public function registerForm(Request $request): Response
    {
        $user = new UserEntity();

        $form = $this->createForm(RegisterFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            return $this->redirectToRoute('register', [
                'nickname' => $formData->getNickname(),
                'email' => $formData->getEmail(),
                'password' => $formData->getPassword(),
            ]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/register/{nickname}/{email}/{password}', name: 'register', requirements: ['nickname' => '.+', 'email' => '.+', 'password' => '.+'])]
    public function register(string $nickname, string $email, string $password, InterfaceUserRepository $userRepository): Response
    {
        if ($userRepository->checkReg($email)) {
            $this->addFlash('error', 'Пользователь с такой почтой уже зарегистрирован.');
            return $this->redirectToRoute('register_form');
        }

        $userRepository->save($nickname, $email, $password);
        $this->addFlash('notice', 'Вы зарегистрировались, теперь вы можете авторизоваться.');
        return $this->redirectToRoute('login_form');
    }

    #[Route('/login_form', name: 'login_form')]
    public function loginForm(Request $request): Response
    {
        $user = new UserEntity();

        $form = $this->createForm(LoginFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            return $this->redirectToRoute('login', [
                'email' => $formData->getEmail(),
                'password' => $formData->getPassword(),
            ]);
        }

        return $this->render('registration/login.html.twig', [
            'loginForm' => $form->createView(),
        ]);
    }

    #[Route('/login/{email}/{password}', name: 'login', requirements: ['email' => '.+', 'password' => '.+'])]
    public function login(string $email, string $password, InterfaceUserRepository $userRepository, RequestStack $requestStack): Response
    {
        $user = $userRepository->logIn($email, $password);
        if ($user) {
            $session = $requestStack->getSession();
            $session->set('user_data', ['nickname' => $user->getNickname(), 'user_id' => $user->getId()]);
            return $this->redirectToRoute('converter_form');
        }else{
            $this->addFlash('error', 'Вы еще не зарегистрировались.');
            return $this->redirectToRoute('login_form');
        }
    }
}
