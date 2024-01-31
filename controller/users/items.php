<?php
require_once('./model/item.php');
require_once('./model/utils.php');
require_once './model/user.php';
require_once './../../vendor/autoload.php'; 

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


function itemsPerUser($id)
{
    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        // tous les items de l'utilisateur
        try {
            $fiche = getItemsFromUser($id);
        } catch (Exception $e) {
            throw new Exception("Unknowed id");
        }
        http_response_code(200);
        sendJSON($fiche);
    } else {
        http_response_code(200);
        throw new Exception("Only GET method accepted");
    }
}

function myItems()
{
    // affiche mes items selon mon tokenId
    if ($_SERVER["REQUEST_METHOD"] === "GET") {

        $token = getDecodedToken();
        $userId = $token['uuid'];

        try {
            $fiche = getItemsFromUser($userId);
        } catch (Exception $e) {
            throw new Exception("Impossible to get this user's items");
        }

        http_response_code(200);
        sendJSON(array("success" => $fiche));
    } else {
        http_response_code(200);
        throw new Exception("Only GET method accepted");
    }
}