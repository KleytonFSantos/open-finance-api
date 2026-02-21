<?php

namespace App\Controller\Auth;

use App\Client\PluggyClient;
use App\Entity\User;
use App\Interface\Auth\RegisterServiceInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Attribute\Route;

readonly class RegisterPostAction
{
    public function __construct(
        private SerializerInterface $serializer,
        private RegisterServiceInterface $userRegistrationService
    ) {
    }

    #[Route('/register', name: 'app_registration', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        try {
            /* @var $user User */
            $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');

            $validationErrors = $this->userRegistrationService->validateUser($user);

            if ($validationErrors && count($validationErrors) > 0) {
                return new JsonResponse(
                    [
                        'error' => (string) $validationErrors[0]->getMessage(),
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            $hashedPassword = $this->userRegistrationService->hashPassword($user->getPassword());
            $user->setPassword($hashedPassword);

            $this->userRegistrationService->register($user);

            return new JsonResponse(
                ['user' => $user->getUserIdentifier()],
                Response::HTTP_OK,
                [],
            );
        } catch (UniqueConstraintViolationException $e) {
            return new JsonResponse(
                [
                    'error' => 'This email is already registered.',
                    'type' => 'Unique Email Constraint'
                ],
                Response::HTTP_BAD_REQUEST,
                [],
            );
        }  catch (\Exception $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR,
                [],
            );
        }
    }
}