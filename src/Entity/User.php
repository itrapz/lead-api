<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
class User implements UserInterface
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    #[Assert\NotBlank]
    private string $firstName;

    #[ORM\Column(length: 32)]
    #[Assert\NotBlank]
    private string $lastName;

    #[ORM\Column(length: 32)]
    #[Assert\NotBlank]
    private string $username;


    #[ORM\Column(length: 180)]
    #[Assert\NotBlank, Assert\Email]
    private string $email;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank]
    private string $phone;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotBlank, Assert\Date]
    private \DateTimeImmutable $dateOfBirth;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $apiToken = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getRoles(): array
    {
        return [];
    }

    public function eraseCredentials(): void {}

    public function getUserIdentifier(): string
    {
        return (string) $this->id;
    }
}
