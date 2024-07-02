<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class AuthController extends ApiController {

    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $encoder): JsonResponse {
        $request = $this->transformJsonBody($request);
        
        $email = $request->get('email');
        $password = $request->get('password');

        if (empty($password) || empty($email)) {
            return $this->respondValidationError("Invalid Password or Email");
        }

        $user = new User($email);
        $user->setPassword($encoder->hashPassword($user, $password));
        $user->setEmail($email);

        $roles = ["ROLE_SUPPORT"];
        $user->setRoles($roles);
        
        $this->em->persist($user);
        $this->em->flush();
        return $this->respondWithSuccess(sprintf('User %s successfully created', $user->getUserIdentifier()));
    }

    #[Route('/auth', name: 'auth', methods: ['POST'])]
    public function auth(UserInterface $user, JWTTokenManagerInterface $JWTManager): JsonResponse {
        return new JsonResponse(['token' => $JWTManager->create($user)]);
        // $JWTManager->create($user);
    }

}