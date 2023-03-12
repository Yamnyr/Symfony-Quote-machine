# Quote Machine

## Installation

Installation des dépendances PHP avec composer :

```shell
composer install
```

Installation des dépendances JavaScript avec npm :

```shell
npm install
```

Compilation des assets :

```shell
npm run dev
npm run build # pour la production
```

Création d'un fichier `.env.local` à partir du fichier `.env` :

```shell
cp .env .env.local
```

Puis modifiez les variables d'environnement du fichier `.env.local` selon votre environnement local.

Mise en place de la base de données :

```shell
composer db
```

## Développement

Lancement du serveur de développement :

```shell
symfony serve
```

Linter et fixer le code avec PHP-CS-Fixer :

```shell
composer cs
```

Exécuter les tests :

```shell
composer test
```

## Intitulé du projet
Un site internet qui permet poster des citations tiré de film, séries... 
propose à un utilisateur de se logger ou de s'inscrire sur l'application. Après authentification et suivant son rôle, l'utilisateur pourra s'il est admin ajouté supprimer, modifié la totalité des citations et des catégories de citations
un utilisateur ne pourra pas créer de catégories et pourra seulement modifier et supprimer les citations que lui-même a postées 
autres fonctionnalités:
- exporter la liste des citations dans un fichier en .csv
- System de ludofication: gains d'exp lors du poste d'une citation visible sur la page profile par le biais d'une jauge représentant la quantité d'exp de l'utilisateur avant le prochain level au coté de la liste des citations postés
-System de recherche
