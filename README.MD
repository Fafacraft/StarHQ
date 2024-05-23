# StarHq
This is a project of a companion app for the game Star Citizen

Very WIP, raw for now.


## INSTALL (in local dev environment)
__Prerequisite__
- A mysql server up and running (https://dev.mysql.com/downloads/mysql/)
- Symfony CLI (https://symfony.com/download)

1) `git clone https://github.com/Fafacraft/StarHQ`
2) `symfony composer require symfony/runtime`  (to install dependencies, this will take time)
3) Change the line 29 in the .env file to link your own database server, or local server
4) `symfony console doctrine:database:create`
5) `symfony console doctrine:migrations:migrate`
6) `symfony server:start`

### To initialize database
go to http://127.0.0.1:8000/load/ships and wait for the db to get ships data from external source. (see progress in console)

WIP



authors ;
Fabien ARTHUR
Matthieu DENIEUL LE DIRAISON

made with Symfony