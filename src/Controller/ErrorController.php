<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ErrorController extends AbstractController
{
    public function showJsonError($exception): JsonResponse
    {
        return $this->json([
            'estado' => 'error',
            'mensaje' => $exception->getMessage()
        ], $exception->getStatusCode());
    }
}
