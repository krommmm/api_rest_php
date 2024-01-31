

# API REST PHP

## Api pour une plateforme d'antiquités

## Technos :

![HTML5](https://img.shields.io/badge/html5-%23E34F26.svg?style=for-the-badge&logo=html5&logoColor=white)
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![CSS3](https://img.shie)

## Description:
Configurer le .env
Créer une BDD.
## Creation tables
### items
uuid varchar(250) primarykey<br/>
posterid varchar(250)
id int index auto_i
name varchar(250)
price id
description text
image varchar(250)
artiste varchar(100)
epoque varchar(100)
style varchar(100)
etat varchar(100)
matiere varchar(100)
longeur int
largeur int
diametre int
hauteur int
profondeur int
img_secondaire_1 varchar(250)
img_secondaire_2 varchar(250)
img_secondaire_3 varchar(250)
img_secondaire_4 varchar(250)
img_secondaire_5 varchar(250)
img_secondaire_6 varchar(250)
img_secondaire_7 varchar(250)
img_secondaire_8 varchar(250)
img_secondaire_9 varchar(250)
img_secondaire_10 varchar(250)

### users
uuid varchar(250) primarykey
id int index auto_i
name varchar(250)
email varchar(250)
password text
avatar varchar(250)
nom_gallerie varchar(250)
site_internet_prive varchar(250)
adresse varchar(250)
tel_fixe varchar(250)
tel_mobile varchar(250)

### my_follow
id int primaryKey auto_i
followed_user_id varchar(250)
follower_user_id varchar(250)
+ vue relationnel onDelete(Cascade) on update(restrict) colonne(follower_user_id) table(users) colonne(uuid)

### my_selection
id int primaryKey auto_i
item_id varchar(250) index
user_id varchar(250) index
+ vue relationnel onDelete(cascade) colonne(item_id) table(items) colonne(uuid)


## Documentation
`localhost/docs`

### Installer composer

### Installer les dépendances:

`composer install`

