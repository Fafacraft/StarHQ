## WORKING DIR TREE
* _suivi\_ : image for docs like suivi.md.
* assets : css, js & other.
* config : guess it.
* migrations : version control of database formats.
* public : images and other public ressources.
* src :
    * Command : Console commands like loading things in the database.
    * Controller : Routes for pages and their logics.
        * Api : subfolder for /api endpoint, which is an api.
    * DataFixtures : Fake Data generator.
    * Entity : All main class like User or Ship.
    * Form : Class to handle forms on the website.
    * Repository : Make queries to the database.
* templates : twig templates / html.
* .env / .env.local : environment variables.
* StarHqMcd.drawio.png : MCD of the database.
* suivi.md : project idea and work process.
* tech_doc.md : this file, with the technical documentation.