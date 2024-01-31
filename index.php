<?php
require_once './controller/items/item.php';
require_once './controller/items/list.php';
require_once './controller/users/user.php';
require_once './controller/users/auth.php';
require_once './controller/users/email.php';
require_once './controller/users/follow.php';
require_once './controller/users/profil.php';
require_once './model/utils.php';
require_once './model/user.php';
require_once './controller/documentation/docs.php';

applyCORS(); 
try {

    if (!empty($_GET['demande'])) {
        $url = explode("/", filter_var($_GET['demande'], FILTER_SANITIZE_URL));

        switch ($url[0]) {

            case "items":
                if (!empty($url[1])) {
                    if ($url[1] === "list") {
                        if (!empty($url[2])) { 
                            addToList($url[2]);
                        } else {
                            getList();
                        }
                    } else {
                        itemById($url[1]);
                    }
                } else {
                    items();
                }
                break;

            case "users": // users/:userId/profil

                if (empty($url[1])) {
                    users();
                } else {
                    switch ($url[1]) {
                        case "signup":
                            signup();
                            break;
                        case "login":
                            login();
                            break;
                        case "profil":

                            if (!empty($url[2])) {
                                switch ($url[2]) {
                                    // disconnect doit se faire du côté front (supprimer le token (storage/cookies));
                                    case "modify":
                                        modifyProfil(); // A finir -------------------------------------------------------------------------------------------
                                        break;
                                    case "delete":
                                        deleteProfil();
                                        break;
                                    default:
                                        userById($url[2]);

                                }
                            } else {
                                myProfil();
                            }
                            break;
                        case "follow":
                            if (!empty($url[2])) {
                                follow($url[2]);
                            } else {
                                throw new Exception("Page unknowed");
                            }
                            break;
                        case "email":
                            if (!empty($url[2])) {
                                sendMail($url[2]); // A vérifier sur vrai serveur (ne marche pas avec localhost)
                            } else {
                                throw new Exception("erreur");
                            }
                            break;
                        case "items":
                            if (!empty($url[2])) {
                                itemsPerUser($url[2]);
                            } else {
                                myItems();
                            }
                            break;
                    }
                }
                break;

            case "docs":
                documentation();
                break;

            default:
                throw new Exception('Page innexistante');
        }

    }
} catch (Exception $e) {
    http_response_code(200);
    sendJSON(array("Error " => $e->getMessage()));
}