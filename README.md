P7-BileMo API
Création de l'API Rest BileMo

## Environnement de développement:
    - Symfony 5.3
    - Composer 1.11
    - WampServer 3.2.5
        - Apache 2.4.46
        - PHP 7.3.21
        - MySQL 5.7.31

## Installation
- Cloner le Repositary GitHub dans le dossier de votre choix: 
```
git clone https://github.com/Gui-Dev86/P7-BileMo.git
```
- Installez les dépendances du projet avec Composer et npm:
```
composer install
```
npm install

- Réalisez une copie du fichier .env nommée .env.local qui devra être crée à la racine du projet. Vous y configurez vos variables d'environnement tel que la connexion à la base de données et votre JWT_PASSPHRASE.

Pour paramétrer votre base de données, modifiez cette ligne avec le nom d'utilisateur, mot de passe et nom de la base de données correespondant (ne pas oublier de retirer le # devant la ligne afin qu'elle soit prise en compte).

    # DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"

exemple : DATABASE_URL="mysql://utilisateur(root de base):mot de passe(vide de base)@127.0.0.1:3306/(nom de la base de données)

Pour paramétrer votre connexion par token JWT [Official documentation](https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md#installation
), attention à bien posséder l'extension openssl

    $ mkdir -p config/jwt
    $ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    $ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout

Une PASSPHRASE sera demandée, attention à bien la noter, afin de la saisir dans votre fichier .env.local

Renseigner vos paramètres de configuration pour le token JWT dans votre ficher .env.local

    ###> lexik/jwt-authentication-bundle ###
    JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
    JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
    JWT_PASSPHRASE=VotrePassePhrase
    ###< lexik/jwt-authentication-bundle ###


- Si elle n'existe pas déjà créez la base de données, depuis le répertoire du projet utilisez la commande:
```
php bin/console doctrine:database:create
```
Générez le fichier de migration des tables de la base de données:
```
php bin/console make:migration
```
Effectez la migration vers la base de données :
```
php bin/console doctrine:migrations:migrate
```

- Si vous souhaitez installer des données fictives afin de bénéficier d'une démo vous pouvez installer les fixtures:
```
php bin/console doctrine:fixtures:load
```
Il existe trois clients afin de tester les fonctionnalités du site:
    pseudo: Orange
    mot de passe: azerty

    pseudo: SFR
    mot de passe: azerty

    pseudo: Bouygues
    mot de passe: azerty

Le projet est maintenant correctement installé. Pour le lancer déplacez vous dans le répertoire du projet et utilisez la commande :
```
$ symfony server:start
```

Pour accéder à la documentation de l'API 
http://localhost:8000/swagger/#/

Auteur Guillaume Vignères - Formation Développeur d'application PHP/Symfony - Openclassroom
