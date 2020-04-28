# My first application with Symfony
Ce code sert uniquement à justifier mes connaissances du développements d'application avec Symfony.

This code is only used to justify my knowledge of application development with Symfony.



# Installation
```
# We clone the deposit!             <-->  On clone le dépot!
git clone https://github.com/h-boudaoud/my-first-app-Symfony.git

# We move in the folder             <-->  On se déplace dans le dossier
cd myFirstAppSymfony

# We install the dependencies!      <-->  On installe les dépendances !
composer install

#  We create the database           <-->  On créé la base de données
php bin/console doctrine:database:create

# We perform the migrations         <-->  On exécute les migrations
php bin/console doctrine:migrations:migrate

#  We execute the fixture           <-->  On exécute la fixture
php bin/console doctrine:fixtures:load --no-interaction

#  We launch the server             <-->  On lance le serveur 
#(exe: run-server.sh)
run-server.sh
```
