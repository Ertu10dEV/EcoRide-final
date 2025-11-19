EcoRide — Version Finale

EcoRide Final est la nouvelle version améliorée d’un premier projet réalisé pour un ECF.
L’ancienne version fonctionnait mais manquait d’organisation : le front et le back étaient mélangés, la logique PHP était directement injectée dans les pages, et le JavaScript n’était presque pas utilisé.
Cette version a été entièrement reconstruite pour proposer un projet plus professionnel et bien structuré, afin d’être présentée comme projet final pour l’examen. Elle sépare clairement le front du back, utilise des API PHP modernes, communique en Fetch / JSON, et intègre à la fois MySQL (SQL) et MongoDB (NoSQL).


Objectif du projet

EcoRide est une application web de covoiturage responsable qui permet de publier un trajet, rechercher un covoiturage, réserver une place et consulter son espace utilisateur.
L’idée est de proposer une expérience fluide, moderne et accessible, tout en respectant une structure de projet réaliste pour un environnement professionnel.

Technologies utilisées

Le projet combine plusieurs technologies :

Front-end : HTML5, CSS3, JavaScript moderne (Fetch API, DOM, localStorage)

Back-end : PHP structuré en API, MySQL pour la base principale, MongoDB pour les logs de recherches

Outils : Composer, Dotenv, XAMPP, Git & GitHub

Cette version met l’accent sur une architecture propre et évolutive, avec une communication front/back claire et un code organisé.

Structure du projet
Voici une représentation simplifiée et fidèle :

## 📁 Structure du projet

```
EcoRide-final/
│
├── back/
│   ├── api/               → Endpoints Fetch (trajets, réservations, session…)
│   ├── config/            → Connexion SQL + variables d'environnement
│   └── controllers/       → Logique métier (login, inscription, publication…)
│
├── front/
│   ├── index.html
│   ├── inscription.html
│   ├── login.html
│   ├── espace-utilisateur.html
│   ├── publier_trajet.html
│   ├── covoiturage.html
│   ├── detail.html
│   ├── resultats.html
│   │
│   ├── css/
│   │   └── styles.css
│   │
│   ├── js/
│   │   ├── auth.js
│   │   ├── search.js
│   │   ├── detail.js
│   │   ├── reservation.js
│   │   ├── trajets.js
│   │   ├── menu.js
│   │   ├── protect.js
│   │   └── user_dashboard.js
│   │
│   └── img/
│       ├── --logo_ECORIDE.png
│       ├── --image-accueil.voiture.eco.jpg
│       ├── default-driver.png
│       └── test-conducteur1.jpg
│
├── composer.json
├── .gitignore
└── README.md
```


Fonctionnalités principales

Cette version intègre :

un système complet d’inscription/connexion sécurisé,
la gestion des trajets publiés et réservés,
une séparation front/back via des endpoints JSON,
des logs enregistrés dans MongoDB,
une interface utilisateur moderne, responsive et soignée.

Tout fonctionne via JavaScript et Fetch, sans rechargements lourds ni formulaires PHP traditionnels.

Installation rapide
Cloner le projet
Placer le dossier dans htdocs
Installer les dépendances PHP dans /back : composer install
Créer un fichier .env avec les accès SQL et MongoDB
Importer la base MySQL
Lancer Apache + MySQL via XAMPP
Ouvrir : http://localhost/EcoRide/front/index.html

Contexte pédagogique

EcoRide Final a été développé pour servir de support concret au dossier projet et illustrer une approche complète : organisation du code, API REST, bases SQL + NoSQL, gestion utilisateur, interface responsive et logique métier cohérente.
C’est une évolution directe du premier EcoRide, qui permet de montrer la progression et les compétences acquises.

## 👤 Auteur

**Ertugrul (Ertu)**  
Développeur Web Full Stack — passionné par le front, le back et les projets web modernes.

🔗 **Portfolio :** [www.d-evweb.fr](https://www.d-evweb.fr)

