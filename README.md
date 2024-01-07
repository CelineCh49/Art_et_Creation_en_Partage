# Art-et-Creation-en-Partage
Projet réalisé avec PHP/Symfony lors du stage pour le titre de  Développeur Web et Web mobile au sein de l'ENI

Projet réalisé seule en 5 semaines - absence de tuteur technique

Réalisation d'une application pour l'association Art et Création en Partage.
Le but est de présenter l'association, visualiser les événements et les artistes participants, tout en offrant à ceux-ci la possibilité de créer et modifier leurs fiches de présentation ainsi que de gérer leur galerie photo. L'application inclut également un back-office pour les administrateurs simplifiant la gestion des utilisateurs, des événements, des artistes et des catégories.



## Technologies utilisées

- PHP: 8.1.1
- Symfony: 6.3
- Twig
- Tailwindcss
- Mysql


## Initialisation du projet

1. **Cloner le dépôt**

   ```bash
   git clone https://github.com/CelineCh49/Art-et-Creation-en-Partage.git
   ```

2. **Installer les dépendances**

   Avec Composer :

   ```bash
   composer install
   npm install
   ```

3. **Configurer la base de données**

   Assurez-vous de mettre à jour le fichier `.env` avec vos informations de connexion à la base de données.

   ```bash
   symfony console doctrine:database:create
   symfony console make:migration
   symfony console doctrine:migrations:migrate
   ```

4. **Lancer le serveur de développement**

   ```bash
   npm run build
   npm run dev
   npm run watch
   symfony serve -d
   ```

   ou si vous utilisez le serveur web PHP intégré :

   ```bash
   php -S localhost:8000 -t public/
   ```
5. **Lancer les fixtures**

   ```bash
   symfony console doctrine:fixtures:load
   ```

   Les utilisateurs, artistes et photos des fixtures ont été modifiées afin de préserver l'anonymat des artistes et les droits d'image. 

## Contribution

Les contributions sont les bienvenues ! Veuillez créer une issue ou soumettre une pull request pour toute contribution.

## Licence

Ce projet est sous licence.
Les images sont issues de pixabay (images libres de droit)


## Auteur
CHAIGNE Céline


