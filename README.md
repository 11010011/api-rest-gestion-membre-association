# Api gestion de membre de l'association

![health check](https://github.com/11010011/api-rest-gestion-membre-association/actions/workflows/laravel.yml/badge.svg)

# pre requis
  - Php 8.1
  - Composer
  - Mysql database
  - Connaissance laravel, php, rest api, phpunit

# installation 
  - Clone repos
  - Aller dans le projet
  - Créer un fichier .env et copier le contenu de .env.example dans .env
  - Créer une base de donnée mysql par rapport à la configuration de base de données dans .env
  - Installation configuration

        composer install
        php artisan key:generate
        php artisan migrate
        php artisan db:seed

  - Lancer le server

        php artisan serve
