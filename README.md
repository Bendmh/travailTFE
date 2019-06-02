# travailTFE
TFE réalisé durant mes études à L'EPHEC
Étape pour installer la plateforme

Commande à effectuer dans le dossier source du serveur

1.	Cloner le repository : 
    ```
    git clone https://github.com/Bendmh/travailTFE.git .
    ```
    
2.	Télécharger les paquets grâce à composer :
    ```
    composer update
    ```
    
3.	Changer le user, password et le nom de la base de données dans le fichier .env :
    ```
    DATABASE_URL=mysql://user:password@127.0.0.1:3306/databaseName
    ```
    
4.	Création de la base de données :
    ```
    php bin/console doctrine:database:create
    ```
    
5.	Création des tables de la base de données :
    ```
    php bin/console doctrine:schema:update –force
    ```
    
6.	Création du super_admin : 
    Dans le fichier /src/DataFixtures/UserFixtures.php, définir votre nom, prénom, pseudo et le mot de passe
    Ensuite lancer la commande : 
    ```
    php bin/console doctrine:fixtures:load
    ```
    
7.	Changer l'environnement et la barre de debug dans .env : 
    ```
    APP_ENV=prod
    APP_DEBUG=0
    ```

8.	Vider le cache : 
    ```
    php bin/console cache:clear
    php bin/console cache:clear --env=prod
    ```
