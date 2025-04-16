<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'api_responses')]
final class ApiResponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private int $id;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: ApiRequest::class, inversedBy: 'responses')]
        #[ORM\JoinColumn(nullable: false)]
        private readonly ApiRequest $apiRequest,
        #[ORM\Column(type: 'smallint')]
        private readonly int $responseStatus,
        #[ORM\Column(type: 'json', nullable: true)]
        private readonly ?array $responseHeaders,
        #[ORM\Column(type: 'text', nullable: true)]
        private readonly ?string $responseBody = null,
    ) {
        $this->createdAt = new \DateTimeImmutable();
    }
}
