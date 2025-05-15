<?php

namespace App\Controller;
#[Route('/formateur')]
class FormateurController extends AbstractController
{
    #[Route('', name: 'formateur_dashboard')]
    public function index(): Response
    {
        return $this->render('formateur/dashboard.html.twig');
    }
}
