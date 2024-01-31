<?php

function documentation() 
{
    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        $doc = "Bienvenue sur l'api pour les antiquités \n fsdfsfs";
        http_response_code(200);
        require_once "./vue/documentation.php";
    } else {
        throw new Exception("Only GET method accepted.");
    }
}