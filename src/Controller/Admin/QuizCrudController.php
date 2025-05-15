<?php

namespace App\Controller\Admin;

use App\Entity\Quiz;
use App\Entity\QuizQuestion;
use App\Form\QuizQuestionType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext; 
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class QuizCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Quiz::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Quiz')
            ->setEntityLabelInPlural('Quiz')
            ->setSearchFields(['titre', 'description'])
            ->setDefaultSort(['dateCreation' => 'DESC']);
    }
    
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('formateur', 'Formateur'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('titre');
        yield TextareaField::new('description')->hideOnIndex();
        
        yield AssociationField::new('formateur')
            ->setFormTypeOption('query_builder', function ($repository) {
                return $repository->createQueryBuilder('u')
                    ->where('u.role = :role')
                    ->setParameter('role', 'formateur')
                    ->orderBy('u.nom', 'ASC');
            });
            
        yield DateTimeField::new('dateCreation')
            ->hideOnForm();
            
        yield CollectionField::new('quizQuestions')
            ->setEntryType(QuizQuestionType::class)
            ->onlyOnForms()
            ->setFormTypeOptions([
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ]);
    }
    
    public function configureActions(Actions $actions): Actions
    {
        $duplicateQuiz = Action::new('duplicateQuiz', 'Dupliquer')
            ->linkToCrudAction('duplicateQuiz');
            
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, $duplicateQuiz)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER);
    }
    
    public function duplicateQuiz(AdminContext $context)
    {
        $originalQuiz = $context->getEntity()->getInstance();
        $entityManager = $this->container->get('doctrine')->getManager();
        
        $newQuiz = new Quiz();
        $newQuiz->setTitre('Copie de ' . $originalQuiz->getTitre());
        $newQuiz->setDescription($originalQuiz->getDescription());
        $newQuiz->setFormateur($originalQuiz->getFormateur());
        
        // Copier les questions
        $ordre = 1;
        foreach ($originalQuiz->getQuizQuestions() as $originalQuizQuestion) {
            $newQuizQuestion = new QuizQuestion();
            $newQuizQuestion->setQuiz($newQuiz);
            $newQuizQuestion->setQuestion($originalQuizQuestion->getQuestion());
            $newQuizQuestion->setOrdre($ordre++);
            $newQuiz->addQuizQuestion($newQuizQuestion);
        }
        
        $entityManager->persist($newQuiz);
        $entityManager->flush();
        
        $this->addFlash('success', 'Le quiz a été dupliqué avec succès.');
        
        $url = $this->container->get(AdminUrlGenerator::class)
            ->setController(self::class)
            ->setAction(Action::DETAIL)
            ->setEntityId($newQuiz->getId())
            ->generateUrl();
            
        return $this->redirect($url);
    }
}