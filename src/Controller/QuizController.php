<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Entity\Score;
use App\Repository\QuizRepository;
use App\Repository\ScoreRepository;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class QuizController extends AbstractController
{
    #[Route('/quiz', name: 'app_quiz_list')]
    public function index(QuizRepository $quizRepository): Response
    {
        return $this->render('quiz/index.html.twig', [
            'quizzes' => $quizRepository->findBy([], ['dateCreation' => 'DESC']),
        ]);
    }

#[Route('/quiz/{id}', name: 'app_quiz_show', requirements: ['id' => '\d+'])]
public function show(int $id, QuizRepository $quizRepository): Response
{
    $quiz = $quizRepository->find($id);
    
    if (!$quiz) {
        throw $this->createNotFoundException('Le quiz demandé n\'existe pas');
    }
    
    return $this->render('quiz/show.html.twig', [
        'quiz' => $quiz,
    ]);
}
#[Route('/quiz/{id}/play', name: 'app_quiz_play', requirements: ['id' => '\d+'])]
public function play(int $id, QuizRepository $quizRepository): Response
{
    $quiz = $quizRepository->find($id);
    
    if (!$quiz) {
        throw $this->createNotFoundException('Le quiz demandé n\'existe pas');
    }
    
    // Vérifier si l'utilisateur est connecté
    if (!$this->getUser()) {
        $this->addFlash('error', 'Vous devez être connecté pour jouer à un quiz');
        return $this->redirectToRoute('app_login');
    }

    return $this->render('quiz/play.html.twig', [
        'quiz' => $quiz,
    ]);
}

 #[Route('/quiz/{id}/submit', name: 'app_quiz_submit', methods: ['POST'])]
    public function submit(int $id, Request $request, QuizRepository $quizRepository, ManagerRegistry $doctrine): Response
    {
        $quiz = $quizRepository->find($id);
        
        if (!$quiz) {
            throw $this->createNotFoundException('Le quiz demandé n\'existe pas');
        }
        
        // Vérifier si l'utilisateur est connecté
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $entityManager = $doctrine->getManager();
        
        // Déboguer les données reçues
        // dump($request->request->all());
        
        $answers = $request->request->all('answers');
        $totalQuestions = count($quiz->getQuizQuestions());
        $correctAnswers = 0;

        // Calculer le score
        foreach ($quiz->getQuizQuestions() as $quizQuestion) {
            $question = $quizQuestion->getQuestion();
            $questionId = $question->getId();
            
            if (isset($answers[$questionId])) {
                $choixId = $answers[$questionId];
                
                foreach ($question->getChoix() as $choix) {
                    if ($choix->getId() == $choixId && $choix->isEstCorrect()) {
                        $correctAnswers++;
                        break;
                    }
                }
            }
        }

        // Calculer le pourcentage
        $scorePercent = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;

        // Enregistrer le score
        $score = new Score();
        $score->setUtilisateur($this->getUser());
        $score->setQuiz($quiz);
        $score->setNote($scorePercent);
        $score->setDatePassage(new \DateTime());

        $entityManager->persist($score);
        $entityManager->flush();

        return $this->render('quiz/result.html.twig', [
            'quiz' => $quiz,
            'score' => $score,
            'correctAnswers' => $correctAnswers,
            'totalQuestions' => $totalQuestions,
        ]);
    }

    #[Route('/api/quiz/{id}/question/{order}', name: 'app_quiz_question_api')]
    public function getQuestion(int $id, int $order, QuizRepository $quizRepository): JsonResponse
    {
        $quiz = $quizRepository->find($id);
        
        if (!$quiz) {
            return $this->json(['error' => 'Quiz not found'], 404);
        }
        
        foreach ($quiz->getQuizQuestions() as $quizQuestion) {
            if ($quizQuestion->getOrdre() == $order) {
                $question = $quizQuestion->getQuestion();
                $choices = [];
                
                foreach ($question->getChoix() as $choix) {
                    $choices[] = [
                        'id' => $choix->getId(),
                        'texte' => $choix->getTexte()
                    ];
                }
                
                return $this->json([
                    'question' => [
                        'id' => $question->getId(),
                        'texte' => $question->getTexte(),
                        'choices' => $choices
                    ]
                ]);
            }
        }
        
        return $this->json(['error' => 'Question not found'], 404);
    }

#[Route('/quiz/categories/{id}', name: 'app_categorie_show', requirements: ['id' => '\d+'])]
public function showByCategory(int $id, CategorieRepository $categorieRepository, QuizRepository $quizRepository): Response
{
    $categorie = $categorieRepository->find($id);
    
    if (!$categorie) {
        throw $this->createNotFoundException('Catégorie non trouvée');
    }
    
    $quizzes = $quizRepository->findByCategory($id);
    
    return $this->render('quiz/by_category.html.twig', [
        'categorie' => $categorie,
        'quizzes' => $quizzes,
    ]);
}

    #[Route('/user/scores', name: 'app_mes_scores')]
    public function userScores(ScoreRepository $scoreRepository): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $scores = $scoreRepository->findBy(['utilisateur' => $this->getUser()], ['datePassage' => 'DESC']);

        return $this->render('user/scores.html.twig', [
            'scores' => $scores,
        ]);
    }

}