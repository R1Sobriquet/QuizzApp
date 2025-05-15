Voici un exemple de fichier README.md pour votre projet Symfony de quizz non terminé. Assurez-vous de personnaliser les sections selon les spécificités de votre projet.

```markdown
# Application de Quizz Symfony

Une application de quizz développée avec Symfony. Le projet est actuellement en cours de développement. Toutes les fonctionnalités marchent sauf l'enregistrement des scores car après qu'un quizz soit réalisé par l'utilisateur, il ne peut pas être soumis.

## Prérequis

- PHP 8.1 ou supérieur
- Composer
- Symfony CLI
- MySQL ou tout autre SGBD compatible avec Doctrine

## Installation

1. Clonez le dépôt :

```bash
git clone https://github.com/R1Sobriquet/QuizzApp.git
cd QuizzApp
```

2. Installez les dépendances avec Composer :

```bash
composer install
```

3. Configurez votre fichier `.env` avec les informations de votre base de données :

```env
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"
```

4. Créez la base de données et exécutez les migrations :

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

5. Chargez les fixtures pour peupler la base de données avec des données de test :

```bash
php bin/console doctrine:fixtures:load
```

## Utilisation

1. Lancez le serveur de développement Symfony :

```bash
symfony serve
```

2. Ouvrez votre navigateur et accédez à l'URL suivante :

```
http://localhost:8000
```

## Fonctionnalités

- Création et gestion des quizz
- Réponse aux questions des quizz
- Affichage des résultats (en cours de développement)

## Problèmes connus

- L'enregistrement des scores ne fonctionne pas. Après qu'un quizz soit réalisé par l'utilisateur, il ne peut pas être soumis.

## Contribution

Les contributions sont les bienvenues ! Pour contribuer, veuillez suivre ces étapes :

1. Fork le projet
2. Créez une branche pour votre fonctionnalité (`git checkout -b feature/AmazingFeature`)
3. Commitez vos changements (`git commit -m 'Add some AmazingFeature'`)
4. Poussez vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrez une Pull Request


