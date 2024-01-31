<?php
require_once('./model/item.php');
require_once('./model/utils.php');
require_once './model/user.php';
require_once './vendor/autoload.php'; 

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../'); 
$dotenv->load();


function myProfil()
{
    if ($_SERVER["REQUEST_METHOD"]) {
        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            $token = getDecodedToken();
            $id = $token['uuid'];
            http_response_code(200);
            sendJSON(array("success" => $id));
        } else {
            http_response_code(200);
            throw new Exception("Only GET method accepted");
        }
    }
}



function getProfil()
{
    if ($_SERVER['REQUEST_METHOD'] === "GET") {
        http_response_code(200);
        sendJSON(array("success" => "Affiche un nombre limité de colonne du profil"));
    } else {
        http_response_code(200);
        throw new Exception("Only GET method accepted");
    }
}

function modifyProfil()
{
    if ($_SERVER['REQUEST_METHOD'] === "POST") {  // PUT
        $token = getDecodedToken();
        $myUserId = $token['uuid'];
        // reçoit formData
        // avatar + nom gallerie + site internet privé + adresse + tel fixe + mobile + nom + email + mdp


        $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : null;
        $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : null;
        $nom_gallerie = isset($_POST['nom_gallerie']) ? htmlspecialchars($_POST['nom_gallerie']) : null;
        $adresse = isset($_POST['adresse']) ? htmlspecialchars($_POST['adresse']) : null;
        $tel_fixe = isset($_POST['tel_fixe']) ? htmlspecialchars($_POST['tel_fixe']) : null;
        $tel_mobile = isset($_POST['tel_mobile']) ? htmlspecialchars($_POST['tel_mobile']) : null;
        $site_internet_prive = isset($_POST['site_internet_prive']) ? htmlspecialchars($_POST['site_internet_prive']) : null;

        $setItems = "";
        $params = array("uuid" => $myUserId);

        // first add text if ? 


        if (isset($_POST['name']) && !empty($_POST['name'])) {
            $setItems .= 'name = :name,';
            $params['name'] = $name;
        }
        if (isset($_POST['email']) && !empty($_POST['email'])) {
            $setItems .= 'email = :email,';
            $params['email'] = $email;
        }
        if (isset($_POST['nom_gallerie']) && !empty($_POST['nom_gallerie'])) {
            $setItems .= 'nom_gallerie = :nom_gallerie,';
            $params['nom_gallerie'] = $nom_gallerie;
        }
        if (isset($_POST['adresse']) && !empty($_POST['adresse'])) {
            $setItems .= 'adresse = :adresse,';
            $params['adresse'] = $adresse;
        }
        if (isset($_POST['tel_fixe']) && !empty($_POST['tel_fixe'])) {
            $setItems .= 'tel_fixe = :tel_fixe,';
            $params['tel_fixe'] = $tel_fixe;
        }
        if (isset($_POST['tel_mobile']) && !empty($_POST['tel_mobile'])) {
            $setItems .= 'tel_mobile = :tel_mobile,';
            $params['tel_mobile'] = $tel_mobile;
        }
        if (isset($_POST['site_internet_prive']) && !empty($_POST['site_internet_prive'])) {
            $setItems .= 'site_internet_prive = :site_internet_prive,';
            $params['site_internet_prive'] = $site_internet_prive;
        }
        $myUser = getUserById($myUserId);
        if (isset($_FILES['avatar']) && !empty($_FILES['avatar'])) {

            // check bdd and get name avatar


            // DELETE FILE FROM SERVER
            if (file_exists('./public/images/' . $myUser[0]['avatar'])) {
                unlink('./public/images/' . $myUser[0]['avatar']);
            }
            $newNameAvatar = saveImageFile($_FILES['avatar']);
            $setItems .= 'avatar = :avatar,';
            $params['avatar'] = $newNameAvatar;
        }

        $setItems = rtrim($setItems, ', ');

        try {

            updateProfil($setItems, $params);
        } catch (Exception $e) {
            throw new Exception("Impossible de modifier le profil" . $e->getMessage());
        }

        http_response_code(200);
        sendJSON(array("success" => "profil updated"));
        //sendJSON(array("success" => "profil updated"));
    } else {
        http_response_code(200);
        throw new Exception("Only POST method accepted");
    }
}

function deleteProfil()
{
    if ($_SERVER['REQUEST_METHOD'] === "DELETE") {
        $token = getDecodedToken();
        $userId = $token['uuid'];
        try {
            deleteMyProfil($userId);
        } catch (Exception $e) {
            throw new Exception("Impossible to delete your profil");
        }
        http_response_code(200);
        sendJSON(array("success" => "Profil deleted"));
    } else {
        http_response_code(200);
        throw new Exception("Only DELETE method accepted");
    }
}
