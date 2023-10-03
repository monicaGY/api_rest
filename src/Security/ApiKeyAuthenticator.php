<?php  
// src/Security/ApiKeyAuthenticator.php
namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class ApiKeyAuthenticator extends AbstractAuthenticator
{

    private $em;
    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }
    public function supports(Request $request): ?bool
    {
        
        return $request->headers->has('X-AUTH-TOKEN');
    }

    public function authenticate(Request $request): Passport
    {

        $apiToken = $request->headers->get('X-AUTH-TOKEN');
        if (null === $apiToken) {
            throw new CustomUserMessageAuthenticationException('Es obligatorio el token');
        }
     
        
        try {
            $decoded = JWT::decode($apiToken, new Key($_ENV['JWT_SECRET'], 'HS256'));
            $id_user = $decoded->iat;
            $user = $this->em->getRepository(User::class)->findOneBy(['id'=>$id_user]);
            
            if(empty($user)){
                
                throw new CustomUserMessageAuthenticationException('No existe el usuario');

            }
            
            return new SelfValidatingPassport(new UserBadge($user->getUsername()));

        } catch (\Throwable $th) {
            throw new CustomUserMessageAuthenticationException('token inválido');
        }
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
?>