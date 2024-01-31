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

