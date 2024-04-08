<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Services\CreerUser;
use App\Services\CreerUserRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @method getDoctrine()
 */
class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['GET']) ]
    public function inscription(Request $request,ValidatorInterface $validateur,UserRepository $userRepository,EntityManagerInterface $entityManager,SerializerInterface $serializer): Response|JsonResponse
    {
        $donnees = json_decode($request->getContent(), true);
        $requete = new CreerUserRequest($donnees["email"],$donnees["password"]);
        $creerUser = new CreerUser($validateur,$userRepository,$entityManager);

        try {
            $user = $creerUser->execute($requete);
            $userSerialized = $serializer->serialize($user, 'json', ['groups' => 'info_user']);
            return new Response($userSerialized, 201, [
                'content-type' => 'application/json'
            ]);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 400);
        }

    }
}