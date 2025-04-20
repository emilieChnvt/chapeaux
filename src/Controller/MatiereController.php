<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Form\MatiereType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MatiereController extends AbstractController
{
    #[Route('/matiere/create', name: 'create_matiere')]
   public function create(EntityManagerInterface $manager, Request $request):Response
    {
        $matiere= new Matiere();
        $matiereForm = $this->createForm(MatiereType::class, $matiere);
        $matiereForm->handleRequest($request);
        if ($matiereForm->isSubmitted() && $matiereForm->isValid()) {
            $manager->persist($matiere);
            $manager->flush();
            return $this->redirectToRoute('chapeaux');
        }
        return $this->render('matiere/index.html.twig', [
            'matiereForm' => $matiereForm->createView(),

        ]);
    }
}
