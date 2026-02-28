<?php

namespace App\Auth\Service;

use App\Auth\Interface\RegisterServiceInterface;
use App\Auth\Entity\User;
use App\Auth\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class RegisterService implements RegisterServiceInterface
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private ValidatorInterface $validator,
        private UserRepository $userRepository
    ) {
    }

    public function hashPassword(string $plainTextPassword): string
    {
        $user = new User();

        return $this->passwordHasher->hashPassword(
            $user,
            $plainTextPassword
        );
    }

    public function validateUser(User $user): ?ConstraintViolationListInterface
    {
        $errors = $this->validator->validate($user);

        if ($errors->count() > 0) {
            return $errors;
        }

        return null;
    }

    public function register(User $user): void
    {
        $this->userRepository->save($user, true);
    }
}