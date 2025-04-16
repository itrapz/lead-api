<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'api_requests')]
final class ApiRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private int $id;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(
        #[ORM\Column(length: 10)]
        private readonly string $requestMethod,
        #[ORM\Column(type: 'text')]
        private readonly string $requestUri,
        #[ORM\Column(type: 'json', nullable: true)]
        private readonly ?array $requestHeaders = [],
        #[ORM\Column(type: 'text', nullable: true)]
        private readonly ?string $requestBody = null,
        #[ORM\Column(length: 45, nullable: true)]
        private readonly ?string $ipAddress = null,
        #[ORM\Column(type: 'text', nullable: true)]
        private readonly ?string $userAgent = null,
    ) {
        $this->createdAt = new \DateTimeImmutable();
    }
}
