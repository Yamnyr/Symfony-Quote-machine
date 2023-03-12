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
