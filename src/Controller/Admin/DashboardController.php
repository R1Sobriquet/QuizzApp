<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Entity\Choix;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\QuizQuestion;
use App\Entity\Score;
use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Option 1. Rediriger vers une page CRUD
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(QuizCrudController::class)->generateUrl());
        
        // Option 2. Afficher un tableau de bord personnalisé
        // return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Quiz App - Administration')
            ->setFaviconPath('favicon.svg')
            ->renderContentMaximized()
            ->setTranslationDomain('admin')
            ->setTextDirection('ltr');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        
        yield MenuItem::section('Quiz');
        yield MenuItem::linkToCrud('Quiz', 'fa fa-question-circle', Quiz::class);
        yield MenuItem::linkToCrud('Questions', 'fa fa-question', Question::class);
        yield MenuItem::linkToCrud('Choix', 'fa fa-list', Choix::class);
        
        yield MenuItem::section('Données');
        yield MenuItem::linkToCrud('Catégories', 'fa fa-tag', Categorie::class);
        yield MenuItem::linkToCrud('Scores', 'fa fa-trophy', Score::class);
        
        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-users', Utilisateur::class);
        
        yield MenuItem::section('Liens');
        yield MenuItem::linkToRoute('Retour au site', 'fa fa-undo', 'app_home');
    }
}