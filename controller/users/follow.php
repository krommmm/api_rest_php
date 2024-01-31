<?php
require_once('./model/item.php');
require_once('./model/utils.php');
require_once './vendor/autoload.php'; 
require_once './model/user.php';




use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function follow($followedUserId)
{
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $token = getDecodedToken();
        $myUserId = $token['uuid'];
        try {
            followUserId($myUserId, $followedUserId);
        } catch (Exception $e) {
            throw new Exception("Impossible de follow cet utilisateur");
        }
        http_response_code(200);
        sendJSON(array("success" => "User followed !"));
    } else if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
        $token = getDecodedToken();
        $myUserId = $token['uuid'];
        try {
            deletedFollowedUser($myUserId, $followedUserId);
        } catch (Exception $e) {
            throw new Exception("Impossible d'unfollow cet utilisateur");
        }
        http_response_code(200);
        sendJSON(array("success" => "User unfollowed !"));
    } else if ($_SERVER["REQUEST_METHOD"] === "GET") {
        $token = getDecodedToken();
        $myUserId = $token['uuid'];
        $myFollowedUsers = getFollowedUser($myUserId);
        http_response_code(200);
        sendJSON(array("success" => $myFollowedUsers));
    } else {
        http_response_code(200);
        throw new Exception("Only GET POST or DELETED methods accepted");
    }
}
