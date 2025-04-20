<?php

namespace App\Controller;

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
}
