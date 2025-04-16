<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\DynamicLeadData;
use App\Entity\Lead;
use App\Message\LeadMessage;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api')]
class LeadController extends AbstractController
{
    #[Route('/leads', name: 'post-lead', methods: [Request::METHOD_POST])]
    public function submit(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        MessageBusInterface $messageBus,
        LoggerInterface $logger,
    ): JsonResponse|Response {
        $data = json_decode($request->getContent(), true);

        try {
            $dynamicDto = $serializer->denormalize($data['dynamicData'] ?? [], DynamicLeadData::class);

            $errors = $validator->validate($dynamicDto);
            if (count($errors) > 0) {
                $logger->warning('Validation failed', [
                    'errors' => (string) $errors,
                    'payload' => $request->getContent()
                ]);

                return $this->errorResponse($errors);
            }
        } catch (\Exception $e) {
            new JsonResponse('Invalid input format: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $lead = Lead::fromArray($data);

        $errors = $validator->validate($lead);
        if (count($errors) > 0) {
            $logger->warning('Validation failed', [
                'errors' => (string) $errors,
                'payload' => $request->getContent()
            ]);

            return $this->errorResponse($errors);
        }

        $messageBus->dispatch(new LeadMessage($lead->toArray()));

        return new Response('', Response::HTTP_CREATED);
    }

    #[Route('/health-check', name: 'health-check', methods: [Request::METHOD_GET])]
    public function healthCheck(
        Request $request
    ): Response {
        return new Response('', Response::HTTP_OK);
    }

    private function errorResponse(ConstraintViolationListInterface $errors): JsonResponse
    {
        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[$error->getPropertyPath()] = $error->getMessage();
        }

        return $this->json(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
    }
}
