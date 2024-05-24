# Star HQ
Une companion webApp pour le jeu Star Citizen, afin de facilité la gestion de ses vaisseaux en jeu.

Par Fabien ARTHUR et Matthieu DENIEUL LE DIRAISON
~
## Intro
__Objectif principal :__
* Pouvoir se connecter et ajouter/modifier les vaisseau de son hangar personnel.

__Objectif secondaires :__
* Voir les vaisseaux d'autres personnes / de notre organisation (lien compte du jeu / compte de l'app)
* Fonctions de tri des vaisseaux
* Comparateur de vaisseaux

__Objectif supplémentaires / idées futurs :__
* Feed d'actualités du jeu, dernières maj
* Demande de prêt / d'équipage entre joueurs (envoie de mail ?)
* WebApp -> +Mobile App (voir la faisabilité)
* Personnalisation du vaisseau (composants, quantum drive)
* Calculateur de temps de trajets
* Calculateur de profits commerciaux

__Hors scope :__
* Interaction avec des données dynamiques (en temps réel) du jeu
* Messagerie / Fonctionnalités avancé de chat
* Vérifications des vaisseaux et de leurs appartenance véritable en jeu
~
## Tech
###
__Symfony :__
MVC ; Model, View, Controllers
* Model : Base de donnée __mysql__, hébergé à part.
* View : __Twig__, html, css, javascript (AJAX) et __bootstrap__
* Controller : __php__
	*  API d'une part, endpoint /api
	* Routes ; gestions des pages, de la redirection et de leurs logique
	* Sécurité : Symfony possède des fonctionnalités d'authentification et de sécurité adaptés.

Possibilités de scripts __python__ ;
* Web scrapping pour des données difficile d'accès comme les joueurs et leurs organisations (sur https://robertsspaceindustries.com/), ou les informations des dernières MAJ
* Fonctionnalités plus lourdes (calculateurs de profits)

Sources ;
* API fan-made pour récupéré des données, notamment sur les vaisseaux https://api.star-citizen.wiki/dashboard

~

## Suivi
__Au 23/05/2024__
* Reflexion sur l'idée du projet, ses fonctionnalités nécessaires et leurs faisabilités, + des idées futurs
* Reflexion sur les technologies à utilisé ; nous en sommes arrivé au framework Symfony, qui convient au projet et offre une base solide de fonctionnalités et de documentations.
* Apprentissage et découverte du framework, confirmation du choix qui convient bien à notre projet
~
* Setup de l'environnement de développement, guide du README sur son installation pour pouvoir travailler sur plusieurs postes aisément
	* Symfony permet notamment facilement de reproduire le schéma de la base de donnée sur tout les environnements existants, facilement, grâce au système de migration.
* Premiers contrôleurs de tests
* Idée d'un visuel pour notre application, sur figma (first_visual_idea.png)
* Début de conception d'un MCD
~
* Début de développement ; fonction de chargement des données de vaisseau depuis l'API externe (https://api.star-citizen.wiki/dashboard) à la db interne  
	* endpoint : /load/ship  
	* import_ship_data.png & src/controller/LoadShipController.php
* Barre de recherche, autocompletion avec __jquery__, et redirection grâce au nom des vaisseaux
	*  Dans l'idée de pouvoir selectionner le vaisseau à a jouter à son hangar, depuis la liste de la bdd.
	* search_bar.png

* Prochaine étape : raffiné le MCD et la bd pour intégré les utilisateurs, ayant chacun une liste de vaisseau personnel. Différence entre vaisseaux personnel, unique et appartenant au joueur, et le modèle du vaisseau et ses statistiques, commun à tout les vaisseau de ce type et ayant ce nom.

~  
__24/05/2024__
* Début de MCD
* Ajout de la classe User, des fonctions de Register/Login/Logout
* Essais de restrictions d'accès selon les rôles
* Modification du chargement des vaisseau ; au lieu d'une route, est devenu une commande console, cela fait plus de sens.  
	* Commande app:load-ships
* Reflexion agencement page principale 
* 1ere iteration d'un template convenable
* Conception du template de l'interface utilisateur pour une navigation fluide et intuitive.
* 1er template emplacement des fonctionnalités