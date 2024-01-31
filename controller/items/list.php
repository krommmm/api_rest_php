<?php
require_once('./model/item.php');
require_once('./model/utils.php');


function addToList($itemId)
{
    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $token = getDecodedToken();
        $userId = $token['uuid'];

        try {
            addItemToMySelection($itemId, $userId);
        } catch (Exception $e) {
            throw new Exception("Impossible d'ajouter cet item à la selection");
        }

        http_response_code(201);
        sendJSON(array("success" => "Item ajouté à la selection ! "));
    } else if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
        $token = getDecodedToken();
        $userId = $token['uuid'];
        try {
            dropItemFromMySelection($itemId, $userId);
            http_response_code(500);
            sendJSON(array("success" => "Item deleted"));
        } catch (Exception $e) {
            throw new Exception("erreur lors de la suppression");
        }
    } else {
        http_response_code(500);
        throw new Exception("Only POST or DELETE methods accepted");
    }
}

function getList()
{
    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        $token = getDecodedToken();
        $userId = $token['uuid'];
        $myItemList = getMyItemList($userId);
        http_response_code(200);
        sendJSON(array("success" => $myItemList));
    } else {
        http_response_code(500);
        throw new Exception("Only GET method accepted");
    }
}
