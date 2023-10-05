<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ErrorController extends AbstractController
{
    public function showJsonError(HttpExceptionInterface $exception): JsonResponse
    {
        // return new JsonResponse($response, $statusCode);
        return $this->json([
            'estado' => 'error',
            'código' => $exception->getStatusCode(),
            'mensaje' => $exception->getMessage()
        ], $exception->getStatusCode());
    }
}
