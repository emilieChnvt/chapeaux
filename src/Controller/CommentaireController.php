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
    #[Route('/commentaire/edit/{id}', name: 'edit_commentaire')]
    public function edit(Commentaire $commentaire, Request $request, EntityManagerInterface $manager): Response
    {
        if(!$commentaire){
            return $this->redirectToRoute('show_chapeau', ['id' => $commentaire->getChapeau()->getId()]);
        }
        $commentaireForm = $this->createForm(CommentaireType::class, $commentaire);
        $commentaireForm->handleRequest($request);
        if($commentaireForm->isSubmitted() && $commentaireForm->isValid()){
            $manager->persist($commentaire);
            $manager->flush();
            return $this->redirectToRoute('show_chapeau', ['id' => $commentaire->getChapeau()->getId()]);
        }
        return $this->render('commentaire/edit.html.twig', [
            'commentaireForm' => $commentaireForm->createView(),
        ]);
    }

    #[Route('/commentaire/delete/{id}', name: 'delete_commentaire')]
    public function delete(Commentaire $commentaire, Request $request, EntityManagerInterface $manager): Response
    {
        if($commentaire){
            $manager->remove($commentaire);
            $manager->flush();
        }

        return $this->redirectToRoute('show_chapeau', ['id' => $commentaire->getChapeau()->getId()]);

    }
}
