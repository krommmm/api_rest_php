<?php
require_once('./model/item.php');
require_once('./model/utils.php');
require_once './model/user.php';
require_once './vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();


function signup()
{

    // récupérération du body
    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $rawData = file_get_contents("php://input");
        $jsonData = json_decode($rawData, true);

        $uuid = getUuid();

        if (!isset($jsonData['name']) || !isset($jsonData['email']) || !isset($jsonData['password']) || !isset($jsonData['motMagique'])) {
            http_response_code(200);
            sendJSON(array('error' => "Des champs du formulaire sont manquants"));
            exit();
        }

        $name = $jsonData['name'] ? htmlspecialchars($jsonData['name']) : null;
        $email = $jsonData['email'] ? htmlspecialchars($jsonData['email']) : null;
        $password = $jsonData['password'] ? htmlspecialchars($jsonData['password']) : null;
        $motMagique = $jsonData['motMagique'] ? htmlspecialchars($jsonData['motMagique']) : null;


        if ($motMagique !== $_ENV['MOT_MAGIQUE']) {
            http_response_code(200);
            sendJSON(array('error' => "Vous n'avez pas le droit de vous inscrire"));
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(401);
            sendJSON(array('error' => "email invalide"));
            exit();
        }


        $emails = getEmails();

        $isEmailTaken = false;
        for ($i = 0; $i < count($emails); $i++) {
            if ($emails[$i]['email'] === $email) {
                $isEmailTaken = true;
            }
        }

        if ($isEmailTaken) {
            http_response_code(401);
            sendJSON(array('error' => "email invalide"));
            exit();
        } else {
            $options = ['cost' => 12];
            $hashPassword = password_hash($password, PASSWORD_BCRYPT, $options);

            $stmt = getUserStmt();
            try {
                $stmt->execute(
                    array(
                        'uuid' => $uuid,
                        'name' => $name,
                        'email' => $email,
                        'password' => $hashPassword
                    )
                );
                http_response_code(200);
                sendJSON(array('success' => "utilisateur ajouté"));
                exit();
            } catch (Exception $e) {
                http_response_code(200);
                sendJSON(array('error' => $e->getMessage()));
            }

        }

    } else {
        http_response_code(200);
        throw new Exception("Only POST method accepted");
    }
}

function login()
{
    // récupérération du body
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $rawData = file_get_contents("php://input");
        $jsonData = json_decode($rawData, true);

        if (!isset($jsonData['email']) || !isset($jsonData['password'])) {
            http_response_code(200);
            sendJSON(array('error' => "Des champs du formulaire sont manquants"));
            exit();
        }

        $email = $jsonData['email'] ? htmlspecialchars($jsonData['email']) : null;
        $password = $jsonData['password'] ? htmlspecialchars($jsonData['password']) : null;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(401);
            sendJSON(array('error' => "email invalide"));
            exit();
        }

        $emailInBdd = getEmails();

        $isEmailTaken = false;
        for ($i = 0; $i < count($emailInBdd); $i++) {
            if ($emailInBdd[$i]['email'] === $email) {
                $isEmailTaken = true;
            }
        }

        if (!$isEmailTaken) {
            http_response_code(401);
            throw new Exception("Paire email/mdp invalide");
        } else {

            // récupérer l'utilisataeur du mail
            $stmtUserFromMail = getUserFromMail();
            $stmtUserFromMail->execute(array("email" => $email));
            $userByMail = $stmtUserFromMail->fetch();

            if (!password_verify($password, $userByMail['password'])) {
                // RECUPERATION FICHE IP du postulant
                $fiche = attemptRequest();
                
                
                // SI sa request echoue, on ajoute une tentative
                addOneTry($fiche);
                http_response_code(401);
                sendJSON(array('error' => "Paire login/mdp invalide"));
                exit();
            } else {
                try {
                    // ajouter clef dotenv
                    $key = $_ENV['SECRET_KEY'];

                    // créer jwt
                    $currentTimestamp = time();
                    $expirationTime = $currentTimestamp + 3600 * 24; // s'expire après 24h
                    $payload = [
                        'uuid' => $userByMail['uuid'],
                        'name' => $userByMail['name'],
                        'exp' => $expirationTime
                    ];


                    $jwt = JWT::encode($payload, $key, 'HS256');
                    // réponse après création jwt
                    $answer = array("message" => "utilisateur connecté", "token" => $jwt);
                    http_response_code(200);
                    sendJSON(array("success" => $answer));
                    exit();
                } catch (Exception $e) {

                    http_response_code(401);
                    sendJSON(array('error' => $e->getMessage()));
                }
            }
        }

    } else {
        http_response_code(200);
        throw new Exception("Only POST method accepted");
    }
}