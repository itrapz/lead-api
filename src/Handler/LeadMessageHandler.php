<?php

declare(strict_types=1);

namespace App\Handler;

use App\Entity\Lead;
use App\Message\LeadMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class LeadMessageHandler
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function __invoke(LeadMessage $message)
    {
        $leadData = $message->leadData;

        $lead = Lead::fromArray($leadData);

        $this->em->persist($lead);
        $this->em->flush();
    }
}
