<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Choix;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\QuizQuestion;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Créer les utilisateurs
        $formateur = new Utilisateur();
        $formateur->setEmail('formateur@example.com');
        $formateur->setPassword($this->passwordHasher->hashPassword($formateur, 'password'));
        $formateur->setNom('Dubois');
        $formateur->setPrenom('Marie');
        $formateur->setRole('formateur');
        $formateur->setRoles(['ROLE_FORMATEUR']);
        $formateur->setDateCreation(new \DateTime());
        $manager->persist($formateur);

        $stagiaire = new Utilisateur();
        $stagiaire->setEmail('stagiaire@example.com');
        $stagiaire->setPassword($this->passwordHasher->hashPassword($stagiaire, 'password'));
        $stagiaire->setNom('Martin');
        $stagiaire->setPrenom('Jean');
        $stagiaire->setRole('stagiaire');
        $stagiaire->setRoles(['ROLE_USER']);
        $stagiaire->setDateCreation(new \DateTime());
        $manager->persist($stagiaire);

        // Créer les catégories
        $categorieCultureG = new Categorie();
        $categorieCultureG->setNom('Culture générale');
        $categorieCultureG->setDescription('Questions de culture générale variées');
        $manager->persist($categorieCultureG);

        $categorieMaths = new Categorie();
        $categorieMaths->setNom('Mathématiques');
        $categorieMaths->setDescription('Questions de mathématiques de différents niveaux');
        $manager->persist($categorieMaths);

        $categorieDev = new Categorie();
        $categorieDev->setNom('Développement logiciel');
        $categorieDev->setDescription('Questions sur la programmation et le développement web');
        $manager->persist($categorieDev);

        // Créer les questions de culture générale
        $question1 = new Question();
        $question1->setTexte('Quelle est la capitale de la France ?');
        $question1->setNiveauDifficulte(1);
        $question1->setCategorie($categorieCultureG);
        $question1->setDateCreation(new \DateTime());
        $manager->persist($question1);

        $choix1_1 = new Choix();
        $choix1_1->setTexte('Paris');
        $choix1_1->setEstCorrect(true);
        $choix1_1->setQuestion($question1);
        $manager->persist($choix1_1);

        $choix1_2 = new Choix();
        $choix1_2->setTexte('Lyon');
        $choix1_2->setEstCorrect(false);
        $choix1_2->setQuestion($question1);
        $manager->persist($choix1_2);

        $choix1_3 = new Choix();
        $choix1_3->setTexte('Marseille');
        $choix1_3->setEstCorrect(false);
        $choix1_3->setQuestion($question1);
        $manager->persist($choix1_3);

        $question2 = new Question();
        $question2->setTexte('Qui a écrit "Les Misérables" ?');
        $question2->setNiveauDifficulte(2);
        $question2->setCategorie($categorieCultureG);
        $question2->setDateCreation(new \DateTime());
        $manager->persist($question2);

        $choix2_1 = new Choix();
        $choix2_1->setTexte('Victor Hugo');
        $choix2_1->setEstCorrect(true);
        $choix2_1->setQuestion($question2);
        $manager->persist($choix2_1);

        $choix2_2 = new Choix();
        $choix2_2->setTexte('Émile Zola');
        $choix2_2->setEstCorrect(false);
        $choix2_2->setQuestion($question2);
        $manager->persist($choix2_2);

        $choix2_3 = new Choix();
        $choix2_3->setTexte('Gustave Flaubert');
        $choix2_3->setEstCorrect(false);
        $choix2_3->setQuestion($question2);
        $manager->persist($choix2_3);

        // Question de mathématiques
        $question3 = new Question();
        $question3->setTexte('Résoudre l\'équation 2x + 3 = 7');
        $question3->setNiveauDifficulte(1);
        $question3->setCategorie($categorieMaths);
        $question3->setDateCreation(new \DateTime());
        $manager->persist($question3);

        $choix3_1 = new Choix();
        $choix3_1->setTexte('x = 2');
        $choix3_1->setEstCorrect(true);
        $choix3_1->setQuestion($question3);
        $manager->persist($choix3_1);

        $choix3_2 = new Choix();
        $choix3_2->setTexte('x = 3');
        $choix3_2->setEstCorrect(false);
        $choix3_2->setQuestion($question3);
        $manager->persist($choix3_2);

        $choix3_3 = new Choix();
        $choix3_3->setTexte('x = 1');
        $choix3_3->setEstCorrect(false);
        $choix3_3->setQuestion($question3);
        $manager->persist($choix3_3);

        // Question de développement
        $question4 = new Question();
        $question4->setTexte('Quel langage de programmation est principalement utilisé pour le développement web côté serveur ?');
        $question4->setNiveauDifficulte(1);
        $question4->setCategorie($categorieDev);
        $question4->setDateCreation(new \DateTime());
        $manager->persist($question4);

        $choix4_1 = new Choix();
        $choix4_1->setTexte('PHP');
        $choix4_1->setEstCorrect(true);
        $choix4_1->setQuestion($question4);
        $manager->persist($choix4_1);

        $choix4_2 = new Choix();
        $choix4_2->setTexte('JavaScript');
        $choix4_2->setEstCorrect(false);
        $choix4_2->setQuestion($question4);
        $manager->persist($choix4_2);

        $choix4_3 = new Choix();
        $choix4_3->setTexte('HTML');
        $choix4_3->setEstCorrect(false);
        $choix4_3->setQuestion($question4);
        $manager->persist($choix4_3);

        // Créer des quiz
        $quiz1 = new Quiz();
        $quiz1->setTitre('Quiz de culture générale');
        $quiz1->setDescription('Testez vos connaissances en culture générale');
        $quiz1->setFormateur($formateur);
        $quiz1->setDateCreation(new \DateTime());
        $manager->persist($quiz1);

        $quizQuestion1 = new QuizQuestion();
        $quizQuestion1->setQuiz($quiz1);
        $quizQuestion1->setQuestion($question1);
        $quizQuestion1->setOrdre(1);
        $manager->persist($quizQuestion1);

        $quizQuestion2 = new QuizQuestion();
        $quizQuestion2->setQuiz($quiz1);
        $quizQuestion2->setQuestion($question2);
        $quizQuestion2->setOrdre(2);
        $manager->persist($quizQuestion2);

        $quiz2 = new Quiz();
        $quiz2->setTitre('Quiz de mathématiques');
        $quiz2->setDescription('Testez vos connaissances en mathématiques');
        $quiz2->setFormateur($formateur);
        $quiz2->setDateCreation(new \DateTime());
        $manager->persist($quiz2);

        $quizQuestion3 = new QuizQuestion();
        $quizQuestion3->setQuiz($quiz2);
        $quizQuestion3->setQuestion($question3);
        $quizQuestion3->setOrdre(1);
        $manager->persist($quizQuestion3);

        $quiz3 = new Quiz();
        $quiz3->setTitre('Quiz de développement web');
        $quiz3->setDescription('Testez vos connaissances en développement web');
        $quiz3->setFormateur($formateur);
        $quiz3->setDateCreation(new \DateTime());
        $manager->persist($quiz3);

        $quizQuestion4 = new QuizQuestion();
        $quizQuestion4->setQuiz($quiz3);
        $quizQuestion4->setQuestion($question4);
        $quizQuestion4->setOrdre(1);
        $manager->persist($quizQuestion4);

        $manager->flush();
    }
}