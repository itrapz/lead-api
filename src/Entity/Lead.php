<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: "leads")]

class Lead
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    private string $firstName;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    private string $lastName;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank, Assert\Email]
    private string $email;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $dynamicData;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank]
    private string $phone;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotBlank]
    private \DateTimeImmutable $dateOfBirth;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(
        string $firstName,
        string $lastName,
        string $email,
        string $phone,
        DateTimeImmutable $dateOfBirth,
        ?array $dynamicData = null,
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phone = $phone;
        $this->dateOfBirth = $dateOfBirth;
        $this->dynamicData = $dynamicData;
        $this->createdAt = new DateTimeImmutable();
    }

    public function toArray(): array
    {
        return [
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
            'dateOfBirth' => $this->dateOfBirth->format(DateTimeImmutable::ATOM),
            'dynamicData' => $this->dynamicData,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['firstName'],
            $data['lastName'],
            $data['email'],
            $data['phone'],
            new \DateTimeImmutable($data['dateOfBirth']),
            $data['dynamicData']
        );
    }
}
