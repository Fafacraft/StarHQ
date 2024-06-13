# StarHq
This is a project of a companion app for the game Star Citizen


## INSTALL
__Prerequisite__
- A mysql server up and running (https://dev.mysql.com/downloads/mysql/)
- Symfony CLI (https://symfony.com/download)
- PHP

1) `git clone https://github.com/Fafacraft/StarHQ`
2) `symfony composer require symfony/runtime`  (to install dependencies, this will take time)
3) Change the line 29 in the .env file to link your own database server, or local server
4) `symfony console doctrine:database:create`
5) `symfony console doctrine:migrations:migrate`
6) Make sure PHP binaries are in the path
7) `symfony console asset-map:compile`  // compile css and images on the prod env
8) `symfony server:start` in local env OR `php -S 0.0.0.0:8000 -t public` for prod

### To initialize database
`symfony console app:load-ships`  // load ships data from external API into the database
`symfony console app:load-ship-images`  // load ships image from the robertspaceindustries.com website, needs to have the ship database loaded.



authors ;
Fabien ARTHUR
Matthieu DENIEUL LE DIRAISON

made with Symfony