<?php

namespace App\Auth\Controller;

use App\Auth\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

readonly class LoginGetAction
{
    #[Route('/login', name: 'api_login', methods: ['POST'])]
    public function index(#[CurrentUser] ?User $user): JsonResponse
    {
        if (!$user) {
            return new JsonResponse([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }
        $token = 'token';

        return new JsonResponse([
                'user' => $user->getUserIdentifier(),
                'token' => $token,
            ]
        );
    }
}