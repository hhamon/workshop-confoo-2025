<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\HealthSpecialist;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ShowHealthSpecialistsController extends AbstractController
{
    #[Route(
        path: '/health-specialists/{id}',
        name: 'app_health_specialist_show',
        defaults: ['section' => 'health_specialist'],
        methods: ['GET']
    )]
    public function __invoke(HealthSpecialist $healthSpecialist): Response
    {
        return $this->render('health_specialist/show.html.twig', [
            'specialist' => $healthSpecialist,
        ]);
    }
}
