<?php

namespace App\Controller;
#[Route('/stagiaire')]
class StagiaireController extends AbstractController
{
    #[Route('', name: 'stagiaire_dashboard')]
    public function index(): Response
    {
        return $this->render('stagiaire/dashboard.html.twig');
    }
}
