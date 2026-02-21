<?php

namespace App\Security;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

readonly class JwtAuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(private JWTManager $jwtManager, private UserRepository $userRepository)
    {
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        $email = $token->getUserIdentifier();

        /** @var UserInterface|null $user */
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            throw new AuthenticationException('User not found.');
        }

        $jwt = $this->jwtManager->create($user);

        return new JsonResponse([
            'token' => $jwt,
            'email' => $email,
        ]);
    }
}