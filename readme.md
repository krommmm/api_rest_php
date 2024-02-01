

# API REST PHP

## Api pour une plateforme d'antiquités

## Technos :

![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)

Sécurité :

Protection contre les injections SQL : validation des entrées (bindParam, PDO), requêtes préparées. <br/>
Protection contre les attaques XSS : filtrage des sorties (fonctions htmlspecialchars)<br/>
Authentification et autorisation : JWT, hash Bcrypt, validation d'e-mail, comparaison avec la base de données<br/>
Protection des données sensibles : fichier .env avec des variables d'environnement pour la connexion et les clés secrètes<br/>
Protection contre la force brute : Blocage de l'adresse IP pendant 1 heure si 3 tentatives d'authentification échouent.<br/>
CORS<br/>
Gestion de toutes les méthodes<br/>


## Description:
Configurer le .env
Créer une BDD.
## Creation tables
### items
uuid varchar(250) primarykey<br/>
posterid varchar(250)<br/>
id int index auto_i<br/>
name varchar(250)<br/>
price id<br/>
description text<br/>
image varchar(250)<br/>
artiste varchar(100)<br/>
epoque varchar(100)<br/>
style varchar(100)<br/>
etat varchar(100)<br/>
matiere varchar(100)<br/>
longeur int<br/>
largeur int<br/>
diametre int<br/>
hauteur int<br/>
profondeur int<br/>
img_secondaire_1 varchar(250)<br/>
img_secondaire_2 varchar(250)<br/>
img_secondaire_3 varchar(250)<br/>
img_secondaire_4 varchar(250)<br/>
img_secondaire_5 varchar(250)<br/>
img_secondaire_6 varchar(250)<br/>
img_secondaire_7 varchar(250)<br/>
img_secondaire_8 varchar(250)<br/>
img_secondaire_9 varchar(250)<br/>
img_secondaire_10 varchar(250)<br/>

### users
uuid varchar(250) primarykey<br/>
id int index auto_i<br/>
name varchar(250)<br/>
email varchar(250)<br/>
password text<br/>
avatar varchar(250)<br/>
nom_gallerie varchar(250)<br/>
site_internet_prive varchar(250)<br/>
adresse varchar(250)<br/>
tel_fixe varchar(250)<br/>
tel_mobile varchar(250)<br/>

### my_follow
id int primaryKey auto_i<br/>
followed_user_id varchar(250)<br/>
follower_user_id varchar(250)<br/>
+ vue relationnel onDelete(Cascade) on update(restrict) colonne<br/>(follower_user_id) table(users) colonne(uuid)<br/>

### my_selection
id int primaryKey auto_i<br/>
item_id varchar(250) index<br/>
user_id varchar(250) index<br/>
+ vue relationnel onDelete(cascade) colonne(item_id) table(items) colonne(uuid)<br/>


## Documentation
`localhost/docs`

### Installer composer

### Installer les dépendances:

`composer install`

