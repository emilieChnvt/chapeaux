<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, EntityManagerInterface $entityManager): Response
    {

        if ($this->getUser()) {
             return $this->redirectToRoute('chapeaux');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/make-admin', name: 'app_make_admin')]

    public function admin(EntityManagerInterface $entityManager, ): Response
    {
        $user =$entityManager->getRepository(User::class)->findOneBy(['email' => 'emilie@gmail.com']);
        if(!$user){
            return new Response('Utilisateur non trouvé');
        }
        $user->setRoles(['ROLE_ADMIN']);
        $entityManager->persist($user);
        $entityManager->flush();

        return new Response('Rôle admin attribué à '.$user->getEmail());

        return $this->render('security/admin.html.twig');

    }
}
