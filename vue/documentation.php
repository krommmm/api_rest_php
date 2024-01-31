<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="public/css/index.css" />
    <title>Document</title>
</head>

<body>
    <header>
        <h1>Documentation API </h1>
    </header>
    <!-- USERS  -->
    <table>
        <tr>
            <th>host</th>
            <th>url[0]</th>
            <th>url[1]</th>
            <th>url[2]</th>
        </tr>
        <tr>
            <td class="host">host</td>
            <td class="user">users</td>
            <td>GET</td>
            <td></td>
        </tr>
        <tr>
            <td class="host">host</td>
            <td class="user">users</td>
            <td class="pink_foncé">signup</td>
            <td>POST: name, email, password, motMagique</td>
        </tr>
        <tr>
            <td class="host">host</td>
            <td class="user">users</td>
            <td class="pink_foncé_plus">login</td>
            <td>POST: email, password</td>
        </tr>
        <tr>
            <td class="host">host</td>
            <td class="user">users</td>
            <td class="profil">profil</td>
            <td>GET</td>
        </tr>
        <tr>
            <td class="host">host</td>
            <td class="user">users</td>
            <td class="profil">profil</td>
            <td class="userId">:userId</td>
            <td>GET</td>
        </tr>
        <tr>
            <td class="host">host</td>
            <td class="user">users</td>
            <td class="profil">profil</td>
            <td class="modify">modify</td>
            <td>POST: name, email, avatar, nom_gallerie, site_internet_prive,adresse,tel_fixe,tel_mobile </td>
        </tr>
        <tr>
            <td class="host">host</td>
            <td class="user">users</td>
            <td class="profil">profil</td>
            <td class="delete">delete</td>
            <td>DELETE</td>
        </tr>
        <tr>
            <td class="host">host</td>
            <td class="user">users</td>
            <td class="follow">follow</td>
            <td class="userId">:userId</td>
            <td>
                <ul>
                    <li>GET</li>
                    <li>POST</li>
                    <li>DELETE</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td class="host">host</td>
            <td class="user">users</td>
            <td class="email">email</td>
            <td class="userId">:userId</td>
            <td>POST</td>
        </tr>
        <tr>
            <td class="host">host</td>
            <td class="user">users</td>
            <td class="items">items</td>
            <td>GET</td>
        </tr>
        <tr>
            <td class="host">host</td>
            <td class="user">users</td>
            <td class="items">items</td>
            <td class="userId">:userId</td>
            <td>GET</td>
        </tr>
        <tr>
            <td class="host">host</td>
            <td class="item">items</td>
            <td>
                <ul>
                    <li>POST: FormData 4 premiers obligatoire de name, price, description, image, artiste, epoque,
                        style, etat, matiere, longeur, largeur, diametre, hauteur, profondeur, img_secondaire_1 à 10
                    </li>
                    <li>GET</li>
                </ul>
            </td>
            <td></td>
        </tr>
        <tr>
            <td class="host">host</td>
            <td class="item">items</td>
            <td class="itemId">:itemId</td>
            <td>
                <ul>
                    <li>POST formData</li>
                    <li>PUT</li>
                    <li>GET</li>
                    <li>DELETE</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td class="host">host</td>
            <td class="item">items</td>
            <td class="list">list</td>
            <td>GET</td>
        </tr>
        <tr>
            <td class="host">host</td>
            <td class="item">items</td>
            <td class="list">list</td>
            <td class="itemId">:itemId</td>
            <td>
                <ul>
                    <li>POST</li>
                    <li>DELETE</li>
                </ul>
            </td>
        </tr>

    </table>

</body>



</html>