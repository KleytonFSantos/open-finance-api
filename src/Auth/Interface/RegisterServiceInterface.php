<?php

namespace App\Auth\Interface;

use App\Auth\Entity\User;
use Symfony\Component\Validator\ConstraintViolationListInterface;

interface RegisterServiceInterface
{
    public function hashPassword(string $plainTextPassword): string;
    public function validateUser(User $user): ?ConstraintViolationListInterface;
    public function register(User $user): void;
}