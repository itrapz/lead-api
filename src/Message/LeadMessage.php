<?php

declare(strict_types=1);

namespace App\Message;

class LeadMessage
{
    public function __construct(
        public array $leadData,
    ) {}
}
