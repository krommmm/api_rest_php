<?php
require_once('./model/utils.php');
require_once('./model/user.php');
require_once 'vendor/autoload.php';
require_once __DIR__ . '/../vendor/autoload.php'; // ajustez ce chemin en fonction de la structure de votre projet
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function getItems()
{
    $pdo = getConnexion();
    $req = 'SELECT * FROM items ORDER BY id';
    $stmt = $pdo->prepare($req);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getItemById($uuid)
{
    $pdo = getConnexion();
    $req = 'SELECT * FROM items WHERE uuid = :uuid';
    $stmt = $pdo->prepare($req);
    $stmt->execute(array("uuid" => $uuid));
    return $stmt->fetch(PDO::FETCH_ASSOC); // Utilisez FETCH_ASSOC pour obtenir uniquement les colonnes associatives
}

function createItem($setItems, $setValues, $params)
{
    $pdo = getConnexion();
    $req = "INSERT INTO items($setItems) VALUES($setValues)";
    $stmt = $pdo->prepare($req);
    $stmt->execute($params);
}

function updateItem($setClause, $params)
{
    $pdo = getConnexion();
    $req = "UPDATE items SET $setClause WHERE uuid = :uuid";
    $stmt = $pdo->prepare($req);
    $stmt->execute($params);
}


function deleteNameItemFromBDD($uuid)
{
    $pdo = getConnexion();
    $req = 'DELETE FROM items WHERE uuid = :uuid';
    $stmt = $pdo->prepare($req);
    $stmt->execute(
        array(
            "uuid" => $uuid
        )
    );
}

function saveImageFile($imageFile)
{
    //DISSECTION FILE
    $tmpName = $imageFile['tmp_name'];
    $name = $imageFile['name'];
    $size = $imageFile['size'];
    $error = $imageFile['error'];

    //RECUPERATION EXTENTION
    $tabExtension = explode('.', $name);
    $extension = strtolower(end($tabExtension));

    //EXTENTIONS ACCEPTED
    $extensions = ['jpg', 'png', 'jpeg', 'webp'];

    //CHECK TAILLE
    $maxSize = 1000000; // = 1mb mais 400000 c'est mieux (penser à compresser les images)

    //date en millisecond (comme Date.now() en js) 
    $timestamp = round(microtime(true) * 1000);

    //RENAME FILE
    $newName = str_replace(".jpg", "", $name);
    $newName = str_replace(".jpeg", "", $newName);
    $newName = str_replace(".png", "", $newName);
    $newName = str_replace(".webp", "", $newName);
    $newName = str_replace(" ", "_", $newName);
    $nouveauNomImage = $newName . $timestamp . "." . $extension;

    if (in_array($extension, $extensions) && $size <= $maxSize && $error == 0) {
        move_uploaded_file($tmpName, './public/images/' . $nouveauNomImage);
        return $nouveauNomImage;
    } else {
        http_response_code(500);
        throw new Exception('problème avec l\'image');
    }
}

function isUuidTokenLikePosterIdItem($item)
{
    //Récupérer le JWT depuis l'en-tête d'autorisation
    $authorizationHeader = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : '';

    // Vérifier si l'en-tête d'autorisation existe et commence par "Bearer"
    if (strpos($authorizationHeader, 'Bearer ') === 0) {
        // Extraire le JWT en supprimant le préfixe "Bearer "
        $jwt = substr($authorizationHeader, 7);

        $key = getKey();

        // DECODED TOKEN  
        try {
            $decoded = JWT::decode($jwt, new Key($key, 'HS256')); // object
        } catch (Exception $e) {
            throw new Exception("Token invalide");
        }
        $decoded_array = (array) $decoded; // ou on convertit l'objet en array


        if ($decoded_array['uuid'] === $item['posterId']) {
            return true;
        } else {
            throw new Exception("L'uuid du token n'est pas égal au posterId de l'item");
        }
    }
}


function addItemToMySelection($itemId, $userId)
{
    $pdo = getConnexion();
    $req = "INSERT INTO my_selection(item_id, user_id) VALUES(:item_id, :user_id) ";
    $stmt = $pdo->prepare($req);
    $stmt->execute(array("item_id" => $itemId, "user_id" => $userId));
}

function dropItemFromMySelection($itemId, $userId)
{
    $pdo = getConnexion();
    $req = "DELETE FROM my_selection where item_id = :itemId";
    $stmt = $pdo->prepare($req);
    $stmt->bindParam(':itemId', $itemId, PDO::PARAM_STR);
    $stmt->execute();
}


