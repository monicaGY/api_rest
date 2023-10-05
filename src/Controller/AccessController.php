<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AccessController extends AbstractController
{


    private $em;
    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }
    #[Route('/api/v1/login')]
    public function login(Request $request): JsonResponse
    {


        $data = json_decode($request->getContent(), true);


        if(empty($data['usuario']) || empty($data['contraseña'])){
            return $this->json([
                'estado' => 'error',
                'mensaje' => 'Faltan parámetros',
            ], 400);
        }
       
        $user = $this->em->getRepository(User::class)->findOneBy(['username'=>$data['usuario']]);
       
        if($data['contraseña'] != $user->getPassword()){
            return $this->json([
                'estado' => 'error',
                'mensaje' => 'Acceso fallido',
            ], 401);
        }

        $payload = [
            'iss' => "http://".dirname($_SERVER['SERVER_NAME']."".$_SERVER['PHP_SELF'])."/",
            'aud' => 'http://example.com',
            'iat' => $user->getId(),
            //el token durará 30 min
            'exp' => time()+(30*60)
        ];



        $jwt = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
        return $this->json([
            'estado' => 'ok',
            'role'=> $user->getRoles(),
            'token' => $jwt,
        ], 200);
    }
}
