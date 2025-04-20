<?php

namespace App\Controller;

use App\Entity\Chapeau;
use App\Repository\ChapeauRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ChapeauController extends AbstractController
{
    #[Route('/', name: 'chapeaux')]
    public function index(ChapeauRepository $repository): Response
    {
        return $this->render('chapeau/index.html.twig', [
            'chapeaux'=>$repository->findAll(),
        ]);
    }

    #[Route('/chapeau/show/{id}', name: 'show_chapeau', priority: -1)]
    public function show(Chapeau $chapeau): Response
    {
        if(!$chapeau){
            return $this->redirectToRoute('chapeaux');
        }
        return $this->render('chapeau/show.html.twig', [
            'chapeau' => $chapeau,
        ]);
    }
}
