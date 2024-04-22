# NexaFrame - CHANG Toma

Ce projet vise à fournir un outil complet pour la création et la gestion de sites vitrines. 
Développé à partir de zéro en utilisant PHP8, ce CMF (Content Management Framework) se base sur le design pattern MVC pour offrir flexibilité et personnalisation à travers un moteur de templating personnalisé et l'intégration d'un framework de composants SASS.

## Prérequis
- Docker
- PHP 8
- Composer
- node_modules

## Étapes à suivre :

#### Étapes 1 :
- Exécuter dans le dossier "app" : "composer install"
- Exécuter dans le dossier "resources" : "npm i"

#### Étapes 2 :
- Exécuter le docker compose via : Docker compose up (-d pour mettre en background)

#### Étapes 3 :
- Aller sur "localhost" dans votre navigateur web
- Vous arriverez sur la page de présentation du CMF
- Pour utiliser le CMF, cliquez sur l'un des boutons "Commencer"

## Bon à savoir :
- Pour la création de page, vous devez créer une page principale avec l'URL "home"
- Le sitemap est accessible depuis localhost/sitemap.xml et est généré automatiquement en fonction des pages créées.
  
### Technologies Utilisées
 
- PHP8
- Docker
- SASS
- GrapesJs
- Amchart
