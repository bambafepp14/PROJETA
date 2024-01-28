Procédure installation projet 

1 - Faire un composer install dans le terminal

2 - refaire la paramétrage pour aller pointer vers ta base de données dans le .env.local
aller modifier la ligne suivante :
ET mettre le bon port correspondant au xamp
DATABASE_URL="mysql://root:@127.0.0.1:3306/db_annonces?serverVersion=mariadb-10.4.27"

3 - supprimer sa base dans phpmyadmin

4 -  dans un terminal taper : 
php bin/console doctrine:database:create

5 - php bin/console make:migration
    
6 - php bin/console doctrine:migrations:migrate

7 - symfony server:start




Procédure 

Comment je crée un admin
1 Crée le futur admin comme si on créait un candidat via profil/new

2 PHP my admin > User > crée un role ROLE_ADMIN

