<?php

namespace App\Entity;

use App\Repository\UserRepository;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['booking_list'])]
    public int $id;

    #[ORM\Column(name: 'name', type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank(message: 'Preencha o campo primeiro nome.')]
    #[Groups(['booking_list'])]
    public ?string $name = null;

    #[ORM\Column(length: 180, unique: true, nullable: false)]
    #[Assert\NotBlank(message: 'Preencha o campo primeiro email.')]
    #[Assert\Email]
    #[Groups(['booking_list'])]
    public string $email;

    #[ORM\Column]
    public array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(nullable: false)]
    #[Assert\NotBlank(message: 'Preencha o campo primeiro password.')]
    public string $password;

    #[Assert\NotBlank]
    #[Assert\Length(min: 5, max: 255)]
    #[Assert\EqualTo(
        propertyPath: 'password',
        message: 'The password is not the same as the password confirmation'
    )]
    public string $password_confirmation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getPasswordConfirmation(): string
    {
        return $this->password_confirmation;
    }

    public function setPasswordConfirmation(string $password_confirmation): void
    {
        $this->password_confirmation = $password_confirmation;
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }
}