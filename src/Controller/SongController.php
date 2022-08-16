<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SongController extends AbstractController
{

    #[Route('/api/songs/{id<\d+>}', methods: ['GET'])]
    public function show(int $id, LoggerInterface $logger): JsonResponse {
        $logger->info("The song of id {id} has been requested", ['id' => $id]);
        return new JsonResponse([
            'id' => $id,
            'name' => 'Waterfalls',
            'url' => 'https://symfonycasts.s3.amazonaws.com/sample.mp3',
        ]);
    }

}