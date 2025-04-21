<?php

namespace App\Controller;

use App\Entity\Chapeau;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CommentaireController extends AbstractController
{
    #[Route('/commentaire/create', name: 'create_commentaire')]
    public function create(EntityManagerInterface $entityManager, Request $request, Chapeau $chapeau): Response
    {

        return $this->render('commentaire/create.html.twig', [
            'commentaireForm' => $commentaireForm->createView(),
        ]);

    }
}
