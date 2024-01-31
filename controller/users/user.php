<?php
require_once('./model/item.php');
require_once('./model/user.php');
require_once('./model/utils.php');
require_once './vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;



$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();


function userById($id)
{
    try {
        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            // installer un middleware d'auth car un utilisateur ne peut consulter que son propre profil

            $user = getUserById($id);
            if (!$user) {
                throw new Exception('ID inconnue');
            }
            $authorizationHeader = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : '';
            if (strpos($authorizationHeader, 'Bearer ') === 0) {
                $jwt = substr($authorizationHeader, 7);

                $key = $_ENV['SECRET_KEY'];
                try {
                    $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
                } catch (Exception $e) {
                    throw new Exception('Token invalide');
                }
                $decoded_array = (array) $decoded;

                if ($decoded_array['uuid'] === $id) {
                    http_response_code(200);
                    sendJSON(array("success" => $user));
                } else {
                    throw new Exception('Vous n\'avez pas accès à cet utilisateur');
                }

            } else {
                http_response_code(200);
                throw new Exception("Not authorized");
            }
        }
    } catch (Exception $e) {
        http_response_code(200);
        sendJSON(array("error" => $e->getMessage()));
    }
}

function users()
{
    if ($_SERVER["REQUEST_METHOD"]) {
        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            http_response_code(200);
            sendJSON(array("error" => 'Non autorisé'));
        } else {
            http_response_code(200);
            throw new Exception("Only GET method accepted");
        }
    }
}



function checkmyselection($userId)
{
    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        // RENVOIE LES ID DE LA SELECTION QUI ON LE MEME USER_id que le userId
        $myToken = getDecodedToken();
        if ($myToken['uuid'] !== $userId) {
            http_response_code(200);
            throw new Exception('Non autorisé, l\'id de l\'utilisateur ne correspond pas à l\'id du token.');
        }
        $mySelection = getMySelection($userId);
        sendJSON($mySelection);
    }
}




