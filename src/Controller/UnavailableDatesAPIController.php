<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Bookable;
use App\service\UnavailableDatesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


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
        $from = new \DateTime($data['from']);
        $to = new \DateTime($data['to']);

        $isIgnoreActive = (bool) ($data['ignoreSelectedUnavailableDate'] ?? false);
        $ignore = $data['ignoreSelectedUnavailableDateId'] ?? null;
        if ($isIgnoreActive) {
            $ignore = $this->unavailableDatesService->findById((int) $ignore);
        }

        $unavailableDates = $this->unavailableDatesService->checkAvailabilityByDate($bookable, $from, $to, $ignore);

        return $this->json(
            $unavailableDates,
            200,
            [],
            ['groups' => 'unavailableDates:read']
        );
    }
}