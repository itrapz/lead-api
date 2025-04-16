<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;

class DynamicLeadData
{
    public function __construct(

    ) {}

    public function toArray(): array
    {
        return $this->fields ?? [];
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[SerializedName('middleName')]
    public string $middleName;

    #[Assert\Type('array')]
    #[SerializedName('hobbies')]
    public ?array $hobbies = null;

    #[Assert\Type('array')]
    #[SerializedName('fields')]
    public ?array $fields = null;
}
