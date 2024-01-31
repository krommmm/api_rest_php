<?php
require_once('./model/item.php');
require_once('./model/utils.php');
require_once './model/user.php';
require_once './vendor/autoload.php'; 

function sendMail($userIdDestination)
{
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $token = getDecodedToken();
        $myUserId = $token['uuid'];

        $myProfil = getUserById($myUserId);
        $hisProfil = getUserById($userIdDestination);

        $rawData = file_get_contents("php://input");
        $jsonData = json_decode($rawData, true);

        if (!isset($jsonData['message'])) {
            $message = $jsonData['message'];
        }

        $destinataire = $hisProfil['email'];
        $sujet = "Email contact antiquités";
        $headers = 'From: ' . $myProfil['email'];

        try {
            mail($destinataire, $sujet, $message, $headers);
        } catch (Exception $e) {
            throw new Exception("Impossible d'envoyer le mail");
        }


        http_response_code(200);
        sendJSON(array("success" => "Email sent"));
    } else {
        http_response_code(200);
        throw new Exception("Only POST method accepted");
    }
}