<?php

namespace App\Controller;

use App\Entity\Chapeau;
use App\Entity\Commentaire;
use App\Entity\User;
use App\Form\ChapeauType;
use App\Form\CommentaireType;
use App\Repository\ChapeauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ChapeauController extends AbstractController
{
    #[Route('/', name: 'chapeaux')]
    public function index(ChapeauRepository $repository): Response
    {
        if(!$user = $this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        return $this->render('chapeau/index.html.twig', [
            'chapeaux'=>$repository->findAll(),
        ]);
    }

    #[Route('/chapeau/show/{id}', name: 'show_chapeau', priority: -1)]
    public function show(Chapeau $chapeau, EntityManagerInterface $entityManager, Request $request): Response
    {
        if(!$chapeau){
            return $this->redirectToRoute('chapeaux');
        }
        $commentaire = new Commentaire();
        $commentaireForm = $this->createForm(CommentaireType::class, $commentaire);
        $commentaireForm->handleRequest($request);
        if ($commentaireForm->isSubmitted() && $commentaireForm->isValid()) {
            $commentaire->setAuteur($this->getUser());
            $commentaire->setChapeau($chapeau);
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('show_chapeau', ['id' => $commentaire->getChapeau()->getId()]);
        }
        return $this->render('chapeau/show.html.twig', [
            'chapeau' => $chapeau,
            'commentaireForm' => $commentaireForm->createView(),
        ]);
    }

    #[Route('/chapeau/create', name: 'create_chapeau')]
    public function create( EntityManagerInterface $manager, Request $request): Response
    {
        if($this->getUser()->getRoles() !== ['ROLE_ADMIN']){
            return $this->redirectToRoute('chapeaux');
        }
        $chapeau = new Chapeau();
        $chapeauForm = $this->createForm(ChapeauType::class, $chapeau);
        $chapeauForm->handleRequest($request);
        if($chapeauForm->isSubmitted() && $chapeauForm->isValid()){
            $manager->persist($chapeau);
            $manager->flush();
            return $this->redirectToRoute('chapeaux');
        }
        return $this->render('chapeau/create.html.twig', [
            'chapeauForm' => $chapeauForm->createView(),
        ]);
    }

    #[Route('/chapeau/edit/{id}', name: 'edit_chapeau')]
    public function edit(Chapeau $chapeau, Request $request, EntityManagerInterface $manager): Response
    {
        if($this->getUser()->getRoles() !== ['ROLE_ADMIN']){
            return $this->redirectToRoute('chapeaux');
        }
        if(!$chapeau){
            return $this->redirectToRoute('chapeaux');
        }
        $chapeauForm = $this->createForm(ChapeauType::class, $chapeau);
        $chapeauForm->handleRequest($request);
        if($chapeauForm->isSubmitted() && $chapeauForm->isValid()){
            $manager->persist($chapeau);
            $manager->flush();
            return $this->redirectToRoute('chapeaux');
        }
        return $this->render('chapeau/edit.html.twig', [
            'chapeauForm' => $chapeauForm->createView(),
        ]);
    }

    #[Route('/chapeau/delete/{id}', name: 'delete_chapeau')]
    public function delete(Chapeau $chapeau, EntityManagerInterface $manager): Response
    {
        if(!$this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('chapeaux');
        }
        if(!$chapeau){
            return $this->redirectToRoute('chapeaux');
        }
        $manager->remove($chapeau);
        $manager->flush();
        return $this->redirectToRoute('chapeaux');
    }
}
