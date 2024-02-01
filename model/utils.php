<?php
require_once 'vendor/autoload.php';
require_once __DIR__ . '/../vendor/autoload.php'; // ajustez ce chemin en fonction de la structure de votre projet
use Ramsey\Uuid\Uuid;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


function getConnexion()
{
    // Utiliser dotenv pour remplir si ce n'est pas en local
    // RECUP KEY DOTENV
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();

    $host = $_ENV['HOST'];
    $dbname = $_ENV['DBNAME'];
    $charset = $_ENV['CHARSET'];
    $user = $_ENV['USER'];
    $password = $_ENV['PASSWORD'];

    try {
        return new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=' . $charset, $user, $password);
    } catch (Exception $e) {
        die('Error : ' . $e->getMessage());
    }
}

function sendJSON($data)
{
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}


function applyCORS()
{
    // Autoriser l'accès depuis n'importe quel domaine
    header("Access-Control-Allow-Origin: *");

    // Autoriser les méthodes HTTP spécifiques
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

    // Autoriser les en-têtes spécifiques
    header("Access-Control-Allow-Headers: Content-Type, Authorization, Accept");

    // // Indiquer si les cookies peuvent être inclus dans les requêtes
    // header("Access-Control-Allow-Credentials: true");

    // // Définir la durée de validité des résultats préalablement exposés en secondes
    // header("Access-Control-Max-Age: 3600");
}

function getKey()
{
    // RECUP KEY DOTENV
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
    $key = $_ENV['SECRET_KEY'];
    return $key;
}

function getUuid()
{
    //CREATION UUID
    $uuid = Uuid::uuid4();
    return $uuid->toString();
}

function getDecodedToken()
{
    //Récupérer le JWT depuis l'en-tête d'autorisation
    $authorizationHeader = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : '';

    // Vérifier si l'en-tête d'autorisation existe et commence par "Bearer"
    if (strpos($authorizationHeader, 'Bearer ') === 0) {
        // Extraire le JWT en supprimant le préfixe "Bearer "
        $jwt = substr($authorizationHeader, 7);

        // RECUP KEY DOTENV
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();
        $key = $_ENV['SECRET_KEY'];

        // DECODED TOKEN  
        try {
            $decoded = JWT::decode($jwt, new Key($key, 'HS256')); // object
        } catch (Exception $e) {
            http_response_code(500);
            throw new Exception('token invalide');
        }
        return (array) $decoded; // ou on convertit l'objet en array
    } else {
        http_response_code(500);
        throw new Exception("Mauvais token / image");
    }
}

// SECURITY
function attemptRequest()
{
    $ip = $_SERVER['REMOTE_ADDR'];
    $fiche = checkIfAspirantExists($ip);
    if (!$fiche) { // cet utilisateur n'avait pas encore essayé de se co
        // donc creation fiche
        createFicheAspirant($ip);
    } else {
        return $fiche;
    }
}
function createFicheAspirant($ip)
{

    $dateActuelle = time();



    $nbEssais = 0;
    $pdo = getConnexion();
    $req = 'INSERT INTO fiche_aspirant(currentTime,timeLeft,ip_adresse,nb_essais) VALUES(:currentTime,:timeLeft,:ip_adresse,:nb_essais) ';
    $stmt = $pdo->prepare($req);
    $stmt->bindParam('ip_adresse', $ip, PDO::PARAM_INT);
    $stmt->bindParam('nb_essais', $nbEssais, PDO::PARAM_INT);
    $stmt->bindParam('currentTime', $dateActuelle, PDO::PARAM_INT);
    $stmt->bindParam('timeLeft', $dateActuelle, PDO::PARAM_INT);

    $stmt->execute();
}
function checkIfAspirantExists($ip)
{
    $pdo = getConnexion();
    $req = 'SELECT * from fiche_aspirant WHERE ip_adresse = :ip_adresse';
    $stmt = $pdo->prepare($req);
    $stmt->bindParam('ip_adresse', $ip, PDO::PARAM_INT);
    $stmt->execute();
    $fiche = $stmt->fetch(PDO::FETCH_ASSOC);
    return $fiche;
}
function addOneTry($fiche)
{
    // $fiche = attemptRequest();
    $nb_essais = $fiche['nb_essais'] + 1;
    if ($nb_essais >= 3) {
        //block en ajoutant 1h au timeLeft
        // ++ checker au début de chaque vérification le time 
        $timeStamp = time();
        $date = new DateTime();
        $date->settimeStamp($timeStamp);
        $date->add(new DateInterval('PT1H'));
        $nouveau_timeStamp = $date->getTimeStamp();
        $fiche['timeLeft'] = $nouveau_timeStamp;


        // $timeLeft = $fiche['timeLeft'] + 1;
        $ip = $_SERVER['REMOTE_ADDR'];
        $pdo = getConnexion();
        $req = 'UPDATE fiche_aspirant SET nb_essais = :nb_essais, timeLeft = :timeLeft WHERE ip_adresse = :ip_adresse';
        $stmt = $pdo->prepare($req);
        $stmt->bindParam('timeLeft', $fiche['timeLeft'], PDO::PARAM_INT);
        $stmt->bindParam('ip_adresse', $ip, PDO::PARAM_INT);
        $stmt->bindParam('nb_essais', $nb_essais, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        $dateActuelle = time();
        $fiche['currentTime'] = $dateActuelle;
        $fiche['timeLeft'] = $dateActuelle;

        $ip = $_SERVER['REMOTE_ADDR'];
        $pdo = getConnexion();
        $req = 'UPDATE fiche_aspirant SET currentTime = :currentTime,timeLeft = :timeLeft,nb_essais = :nb_essais WHERE ip_adresse = :ip_adresse';
        $stmt = $pdo->prepare($req);
        $stmt->bindParam('nb_essais', $nb_essais, PDO::PARAM_INT);
        $stmt->bindParam('ip_adresse', $ip, PDO::PARAM_INT);
        $stmt->bindParam('currentTime', $fiche['currentTime'], PDO::PARAM_INT);
        $stmt->bindParam('timeLeft', $fiche['timeLeft'], PDO::PARAM_INT);
        $stmt->execute();
    }

}

function isForbidden()
{
    $ip = $_SERVER['REMOTE_ADDR'];
    $fiche = attemptRequest();
    if ($fiche) {
        $dateActuelle = time();
        //MAJ DE LA DATE ACTUELLE
        $fiche['currentTime'] = $dateActuelle;
        $pdo = getConnexion();
        $req = 'UPDATE fiche_aspirant SET currentTime = :currentTime,nb_essais = :nb_essais WHERE ip_adresse = :ip_adresse';
        $stmt = $pdo->prepare($req);
        $stmt->bindParam('nb_essais', $nb_essais, PDO::PARAM_INT);
        $stmt->bindParam('ip_adresse', $ip, PDO::PARAM_INT);
        $stmt->bindParam('currentTime', $fiche['currentTime'], PDO::PARAM_INT);
        $stmt->execute();

        if ($fiche['timeLeft'] > $dateActuelle) {
            
            http_response_code(200);
            echo json_encode("vous plus le droit de requêter", JSON_UNESCAPED_UNICODE);
            exit();
        }
    }
}
