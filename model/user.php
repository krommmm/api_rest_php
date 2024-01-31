<?php
require_once('./model/utils.php');


function getEmails()
{
    $pdo = getConnexion();
    $req = 'SELECT email FROM users ORDER BY id';
    $stmt = $pdo->prepare($req);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUserStmt()
{
    $pdo = getConnexion();
    $req = 'INSERT INTO users(uuid, name, email, password) VALUES(:uuid, :name,:email,:password)';
    return $pdo->prepare($req);
}

function getUserFromMail()
{
    $pdo = getConnexion();
    $req = 'SELECT * FROM users WHERE email = :email';
    return $pdo->prepare($req);
}

function getUserById($uuid)
{
    $pdo = getConnexion();
    $req = 'SELECT * FROM users WHERE uuid = :uuid';
    $stmt = $pdo->prepare($req);
    try {
        $stmt->execute(array("uuid" => $uuid));
    } catch (Exception $e) {
        throw new Exception("Impossible d'obtenir l'utilisateur");
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Utilisez FETCH_ASSOC pour obtenir uniquement les colonnes associatives
}

function getMySelection($myUserId)
{
    $pdo = getConnexion();
    $req = 'SELECT item_id FROM my_selection WHERE user_id = :user_id';
    $stmt = $pdo->prepare($req);
    $stmt->execute(array("user_id" => $myUserId));
    $mySelection = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $mySelection;
}

function deleteMyProfil($userId)
{
    $pdo = getConnexion();
    $req = 'DELETE FROM users WHERE uuid = :userid';
    $stmt = $pdo->prepare($req);
    $stmt->bindParam('userid', $userId, PDO::PARAM_STR);
    $stmt->execute();
}

function getMyItemList($userId)
{
    $pdo = getConnexion();
    $req = 'SELECT item_id FROM my_selection WHERE user_id = :userId';
    $stmt = $pdo->prepare($req);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
    $stmt->execute();
    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $list;
}

function getItemsFromUser($id)
{
    $pdo = getConnexion();
    // on peut juste donner les info de l'item
    $req = 'SELECT i.uuid, i.id,i.name, i.price, i.description, i.artiste, i.epoque, i.style, i.etat, i.matiere, i.longeur, i.largeur, i.diametre, i.hauteur, i.profondeur,i.image, i.img_secondaire_1,i.img_secondaire_2,i.img_secondaire_3,img_secondaire_4,img_secondaire_5,img_secondaire_6,img_secondaire_7,img_secondaire_8,img_secondaire_9,img_secondaire_10 FROM users u INNER JOIN items i ON u.uuid = i.posterId WHERE u.uuid = :userId';
    $stmt = $pdo->prepare($req);
    $stmt->bindParam(':userId', $id, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function followUserId($myUserId, $followedUserId)
{
    $pdo = getConnexion();
    $req = 'INSERT INTO my_follow(follower_user_id, followed_user_id) VALUES(:myUserId, :followedUserId)';
    $stmt = $pdo->prepare($req);
    $stmt->bindParam(':myUserId', $myUserId, PDO::PARAM_STR);
    $stmt->bindParam(':followedUserId', $followedUserId, PDO::PARAM_STR);
    $stmt->execute();
}

function deletedFollowedUser($myUserId, $followedUserId)
{
    $pdo = getConnexion();
    $req = 'DELETE FROM my_follow WHERE follower_user_id = :myUserId AND followed_user_id = :followedUserId';
    $stmt = $pdo->prepare($req);
    $stmt->bindParam(':myUserId', $myUserId, PDO::PARAM_STR);
    $stmt->bindParam(':followedUserId', $followedUserId, PDO::PARAM_STR);
    $stmt->execute();
}

function getFollowedUser($myUserId)
{
    $pdo = getConnexion();
    $req = 'SELECT followed_user_id FROM my_follow WHERE follower_user_id = :myUserId';
    $stmt = $pdo->prepare($req);
    $stmt->bindParam('myUserId', $myUserId, PDO::PARAM_STR);
    $stmt->execute();
    $myFollowedUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $myFollowedUsers;
}

function updateProfil($setItems, $params)
{
    $pdo = getConnexion();
    $req = "UPDATE users SET $setItems WHERE uuid = :uuid";
    $stmt = $pdo->prepare($req);
    $stmt->execute($params);
}