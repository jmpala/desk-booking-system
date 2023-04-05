<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Bookable;
use App\Service\UnavailableDatesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UnavailableDatesAPIController extends AbstractController
{
    public function __construct(
        private UnavailableDatesService $unavailableDatesService,
    )
    {}

    #[Route('/api/admin/unavailableDates/{id}', name: 'app_unavailabledatesapi_checkavailabilitybydate', methods: ['POST'])]
    public function checkAvailabilityByDate(Bookable $bookable, Request $request): Response {
        $data = json_decode(
            $request->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        return $this->json(
            $this->unavailableDatesService->checkAvailabilityByDate(
                $bookable,
                new \DateTime($data['from']),
                new \DateTime($data['to']),
                $data['ignoreSelectedUnavailableDate']
                    ? $this->unavailableDatesService->findById((int) $data['ignoreSelectedUnavailableDateId'])
                    : null
            ),
            200,
            [],
            ['groups' => 'unavailableDates:read']
        );
    }
}