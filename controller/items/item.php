<?php
require_once('./model/item.php');
require_once('./model/user.php');
require_once('./model/utils.php');


use Firebase\JWT\JWT;
use Firebase\JWT\Key;



function items()
{
    if ($_SERVER["REQUEST_METHOD"]) {

        switch ($_SERVER['REQUEST_METHOD']) {
            case "POST":

                try {
                    //CHECK FORMULAIRE REMPLIT
                    if (!isset($_POST['name']) || empty($_POST['name']) || !isset($_POST['price']) || empty($_POST['price']) || !isset($_POST['description']) || empty($_POST['description']) || !isset($_FILES['image']) || empty($_FILES['image'])) {
                        http_response_code(500);
                        throw new Exception("Le formulaire n'est pas remplit correctement");
                    }

                    //SECURITEE INJECTION SQL
                    $nameString = htmlspecialchars($_POST['name']);
                    $price = htmlspecialchars($_POST['price']);
                    $description = htmlspecialchars($_POST['description']);

                    $nouveauNomImage = saveImageFile($_FILES['image']);

                    // CREATION UUID
                    $uuidString = getUuid();

                    $decoded_array = getDecodedToken();

                    $setItems = "posterId,uuid,name,price,description,image,";
                    $setValues = ":posterId,:uuid,:name,:price,:description,:image,";
                    $params = array(
                        "posterId" => $decoded_array['uuid'],
                        "uuid" => $uuidString,
                        "name" => $nameString,
                        "price" => $price,
                        "description" => $description,
                        "image" => $nouveauNomImage
                    );


                    $artiste = isset($_POST['artiste']) ? htmlspecialchars($_POST['artiste']) : null;
                    $epoque = isset($_POST['epoque']) ? htmlspecialchars($_POST['epoque']) : null;
                    $style = isset($_POST['style']) ? htmlspecialchars($_POST['style']) : null;
                    $etat = isset($_POST['etat']) ? htmlspecialchars($_POST['etat']) : null;
                    $matiere = isset($_POST['matiere']) ? htmlspecialchars($_POST['matiere']) : null;
                    $longeur = isset($_POST['longeur']) ? htmlspecialchars($_POST['longeur']) : null;
                    $largeur = isset($_POST['largeur']) ? htmlspecialchars($_POST['largeur']) : null;
                    $diametre = isset($_POST['diametre']) ? htmlspecialchars($_POST['diametre']) : null;
                    $hauteur = isset($_POST['hauteur']) ? htmlspecialchars($_POST['hauteur']) : null;
                    $profondeur = isset($_POST['profondeur']) ? htmlspecialchars($_POST['profondeur']) : null;


                    if (isset($artiste) && !empty($artiste)) {
                        $setItems .= 'artiste,';
                        $setValues .= ':artiste,';
                        $params['artiste'] = $artiste;
                    }
                    if (isset($epoque) && !empty($epoque)) {
                        $setItems .= 'epoque, ';
                        $setValues .= ':epoque, ';
                        $params['epoque'] = $epoque;
                    }
                    if (isset($style) && !empty($style)) {
                        $setItems .= 'style, ';
                        $setValues .= ':style, ';
                        $params['style'] = $style;
                    }
                    if (isset($etat) && !empty($etat)) {
                        $setItems .= 'etat, ';
                        $setValues .= ':etat, ';
                        $params['etat'] = $etat;
                    }
                    if (isset($matiere) && !empty($matiere)) {
                        $setItems .= 'matiere, ';
                        $setValues .= ':matiere, ';
                        $params['matiere'] = $matiere;
                    }
                    if (isset($longeur) && !empty($longeur)) {
                        $setItems .= 'longeur, ';
                        $setValues .= ':longeur, ';
                        $params['longeur'] = $longeur;
                    }
                    if (isset($largeur) && !empty($largeur)) {
                        $setItems .= 'largeur, ';
                        $setValues .= ':largeur, ';
                        $params['largeur'] = $largeur;
                    }
                    if (isset($diametre) && !empty($diametre)) {
                        $setItems .= 'diametre, ';
                        $setValues .= ':diametre, ';
                        $params['diametre'] = $diametre;
                    }
                    if (isset($hauteur) && !empty($hauteur)) {
                        $setItems .= 'hauteur, ';
                        $setValues .= ':hauteur, ';
                        $params['hauteur'] = $hauteur;
                    }
                    if (isset($profondeur) && !empty($profondeur)) {
                        $setItems .= 'profondeur, ';
                        $setValues .= ':profondeur, ';
                        $params['profondeur'] = $profondeur;
                    }
                    // IMAGES SECONDAIRES
                    if (isset($_FILES['img_secondaire_1']) && !empty($_FILES['img_secondaire_1'])) {
                        // SAVE FILE IN SERVER
                        $imgSecondaire1 = saveImageFile($_FILES['img_secondaire_1']);

                        // SAVE IN BDD
                        $setItems .= 'img_secondaire_1,';
                        $setValues .= ':img_secondaire_1,';
                        $params['img_secondaire_1'] = $imgSecondaire1;
                    }
                    if (isset($_FILES['img_secondaire_2']) && !empty($_FILES['img_secondaire_2'])) {
                        // SAVE FILE IN SERVER
                        $imgSecondaire2 = saveImageFile($_FILES['img_secondaire_2']);

                        // SAVE IN BDD
                        $setItems .= 'img_secondaire_2,';
                        $setValues .= ':img_secondaire_2,';
                        $params['img_secondaire_2'] = $imgSecondaire2;
                    }
                    if (isset($_FILES['img_secondaire_3']) && !empty($_FILES['img_secondaire_3'])) {
                        // SAVE FILE IN SERVER
                        $imgSecondaire3 = saveImageFile($_FILES['img_secondaire_3']);

                        // SAVE IN BDD
                        $setItems .= 'img_secondaire_3,';
                        $setValues .= ':img_secondaire_3,';
                        $params['img_secondaire_3'] = $imgSecondaire3;
                    }
                    if (isset($_FILES['img_secondaire_4']) && !empty($_FILES['img_secondaire_4'])) {
                        // SAVE FILE IN SERVER
                        $imgSecondaire4 = saveImageFile($_FILES['img_secondaire_4']);

                        // SAVE IN BDD
                        $setItems .= 'img_secondaire_4,';
                        $setValues .= ':img_secondaire_4,';
                        $params['img_secondaire_4'] = $imgSecondaire4;
                    }
                    if (isset($_FILES['img_secondaire_5']) && !empty($_FILES['img_secondaire_5'])) {
                        // SAVE FILE IN SERVER
                        $imgSecondaire5 = saveImageFile($_FILES['img_secondaire_5']);

                        // SAVE IN BDD
                        $setItems .= 'img_secondaire_5,';
                        $setValues .= ':img_secondaire_5,';
                        $params['img_secondaire_5'] = $imgSecondaire5;
                    }
                    if (isset($_FILES['img_secondaire_6']) && !empty($_FILES['img_secondaire_6'])) {
                        // SAVE FILE IN SERVER
                        $imgSecondaire6 = saveImageFile($_FILES['img_secondaire_6']);

                        // SAVE IN BDD
                        $setItems .= 'img_secondaire_6,';
                        $setValues .= ':img_secondaire_6,';
                        $params['img_secondaire_6'] = $imgSecondaire6;
                    }
                    if (isset($_FILES['img_secondaire_7']) && !empty($_FILES['img_secondaire_7'])) {
                        // SAVE FILE IN SERVER
                        $imgSecondaire7 = saveImageFile($_FILES['img_secondaire_7']);

                        // SAVE IN BDD
                        $setItems .= 'img_secondaire_7,';
                        $setValues .= ':img_secondaire_7,';
                        $params['img_secondaire_7'] = $imgSecondaire7;
                    }
                    if (isset($_FILES['img_secondaire_8']) && !empty($_FILES['img_secondaire_8'])) {
                        // SAVE FILE IN SERVER
                        $imgSecondaire8 = saveImageFile($_FILES['img_secondaire_8']);

                        // SAVE IN BDD
                        $setItems .= 'img_secondaire_8,';
                        $setValues .= ':img_secondaire_8,';
                        $params['img_secondaire_8'] = $imgSecondaire8;
                    }
                    if (isset($_FILES['img_secondaire_9']) && !empty($_FILES['img_secondaire_9'])) {
                        // SAVE FILE IN SERVER
                        $imgSecondaire9 = saveImageFile($_FILES['img_secondaire_9']);

                        // SAVE IN BDD
                        $setItems .= 'img_secondaire_9,';
                        $setValues .= ':img_secondaire_9,';
                        $params['img_secondaire_9'] = $imgSecondaire8;
                    }
                    if (isset($_FILES['img_secondaire_10']) && !empty($_FILES['img_secondaire_10'])) {
                        // SAVE FILE IN SERVER
                        $imgSecondaire10 = saveImageFile($_FILES['img_secondaire_10']);

                        // SAVE IN BDD
                        $setItems .= 'img_secondaire_10,';
                        $setValues .= ':img_secondaire_10,';
                        $params['img_secondaire_10'] = $imgSecondaire10;
                    }



                    $setItems = rtrim($setItems, ', ');
                    $setValues = rtrim($setValues, ', ');



                    createItem($setItems, $setValues, $params);
                    http_response_code(201);
                    sendJSON(array("success" => "Item créé !"));

                } catch (Exception $e) {
                    http_response_code(500);
                    sendJSON(array("error" => $e->getMessage()));
                }

                break;

            case "GET":
                try {
                    $items = getItems();

                    // on enlève le posterId
                    foreach ($items as &$produit) {
                        // Utilisez unset pour supprimer la clé "posterId"
                        unset($produit['posterId']);
                    }
                 
                    http_response_code(200);
                    sendJSON($items);
                } catch (Exception $e) {
                    http_response_code(500);
                    sendJSON(array('error' => $e->getMessage()));
                }
                break;
        }
    }


}

function itemById($id)
{
    if ($_SERVER["REQUEST_METHOD"]) {

        switch ($_SERVER['REQUEST_METHOD']) {
            // ICI CEST LE PUT mais en php c'est aussi le 
            case "POST":
                try {

                    $item = getItemById($id);
                    if (!$item) {
                        throw new Exception("ID incorrecte");
                    }

                    $isProprio = isUuidTokenLikePosterIdItem($item);
                    if (!$isProprio) {
                        throw new Exception("Token invalide");
                    }

                    $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : null;
                    $price = isset($_POST['price']) ? htmlspecialchars($_POST['price']) : null;
                    $description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : null;
                    $artiste = isset($_POST['artiste']) ? htmlspecialchars($_POST['artiste']) : null;
                    $epoque = isset($_POST['epoque']) ? htmlspecialchars($_POST['epoque']) : null;
                    $style = isset($_POST['style']) ? htmlspecialchars($_POST['style']) : null;
                    $etat = isset($_POST['etat']) ? htmlspecialchars($_POST['etat']) : null;
                    $matiere = isset($_POST['matiere']) ? htmlspecialchars($_POST['matiere']) : null;
                    $longeur = isset($_POST['longeur']) ? htmlspecialchars($_POST['longeur']) : null;
                    $largeur = isset($_POST['largeur']) ? htmlspecialchars($_POST['largeur']) : null;
                    $diametre = isset($_POST['diametre']) ? htmlspecialchars($_POST['diametre']) : null;
                    $hauteur = isset($_POST['hauteur']) ? htmlspecialchars($_POST['hauteur']) : null;
                    $profondeur = isset($_POST['profondeur']) ? htmlspecialchars($_POST['profondeur']) : null;


                    $setClause = "";
                    $params = array("uuid" => $id);
                    $nouveauNomImage = ""; // var nom image en attente

                    // EXCEPTION FOR IMAGE: need suppress file old & add new from server + give name for BDD
                    if (isset($_FILES['image']) && !empty($_FILES['image'])) {

                        // DELETE FILE FROM SERVER
                        if (file_exists('./public/images/' . $item['image'])) {
                            unlink('./public/images/' . $item['image']);
                        }
                        // SAVE FILE IN SERVER
                        $nouveauNomImage = saveImageFile($_FILES['image']);

                        $setClause .= "image = :image,";
                        $params['image'] = $nouveauNomImage;
                    }

                    if (isset($_FILES['img_secondaire_1']) && !empty($_FILES['img_secondaire_1'])) {
                        if (file_exists('./public/images/' . $item['img_secondaire_1'])) {
                            unlink('./public/images/' . $item['img_secondaire_1']);
                        }
                        $newImgSecondaire1 = saveImageFile($_FILES['img_secondaire_1']);
                        $setClause .= "img_secondaire_1 = :img_secondaire_1,";
                        $params['img_secondaire_1'] = $newImgSecondaire1;
                    }
                    if (isset($_FILES['img_secondaire_2']) && !empty($_FILES['img_secondaire_2'])) {
                        if (file_exists('./public/images/' . $item['img_secondaire_2'])) {
                            unlink('./public/images/' . $item['img_secondaire_2']);
                        }
                        $newImgSecondaire2 = saveImageFile($_FILES['img_secondaire_2']);
                        $setClause .= "img_secondaire_2 = :img_secondaire_2,";
                        $params['img_secondaire_2'] = $newImgSecondaire2;
                    }
                    if (isset($_FILES['img_secondaire_3']) && !empty($_FILES['img_secondaire_3'])) {
                        if (file_exists('./public/images/' . $item['img_secondaire_3'])) {
                            unlink('./public/images/' . $item['img_secondaire_3']);
                        }
                        $newImgSecondaire3 = saveImageFile($_FILES['img_secondaire_3']);
                        $setClause .= "img_secondaire_3 = :img_secondaire_3,";
                        $params['img_secondaire_3'] = $newImgSecondaire3;
                    }

                    if (isset($_FILES['img_secondaire_4']) && !empty($_FILES['img_secondaire_4'])) {
                        if (file_exists('./public/images/' . $item['img_secondaire_4'])) {
                            unlink('./public/images/' . $item['img_secondaire_4']);
                        }
                        $newImgSecondaire4 = saveImageFile($_FILES['img_secondaire_4']);
                        $setClause .= "img_secondaire_4 = :img_secondaire_4,";
                        $params['img_secondaire_4'] = $newImgSecondaire4;
                    }
                    if (isset($_FILES['img_secondaire_5']) && !empty($_FILES['img_secondaire_5'])) {
                        if (file_exists('./public/images/' . $item['img_secondaire_5'])) {
                            unlink('./public/images/' . $item['img_secondaire_5']);
                        }
                        $newImgSecondaire5 = saveImageFile($_FILES['img_secondaire_5']);
                        $setClause .= "img_secondaire_5 = :img_secondaire_5,";
                        $params['img_secondaire_5'] = $newImgSecondaire5;
                    }

                    if (isset($_FILES['img_secondaire_6']) && !empty($_FILES['img_secondaire_6'])) {
                        if (file_exists('./public/images/' . $item['img_secondaire_6'])) {
                            unlink('./public/images/' . $item['img_secondaire_6']);
                        }
                        $newImgSecondaire6 = saveImageFile($_FILES['img_secondaire_6']);
                        $setClause .= "img_secondaire_6 = :img_secondaire_6,";
                        $params['img_secondaire_6'] = $newImgSecondaire6;
                    }

                    if (isset($_FILES['img_secondaire_7']) && !empty($_FILES['img_secondaire_7'])) {
                        if (file_exists('./public/images/' . $item['img_secondaire_7'])) {
                            unlink('./public/images/' . $item['img_secondaire_7']);
                        }
                        $newImgSecondaire7 = saveImageFile($_FILES['img_secondaire_7']);
                        $setClause .= "img_secondaire_7 = :img_secondaire_7,";
                        $params['img_secondaire_7'] = $newImgSecondaire7;
                    }

                    if (isset($_FILES['img_secondaire_8']) && !empty($_FILES['img_secondaire_8'])) {
                        if (file_exists('./public/images/' . $item['img_secondaire_8'])) {
                            unlink('./public/images/' . $item['img_secondaire_8']);
                        }
                        $newImgSecondaire8 = saveImageFile($_FILES['img_secondaire_8']);
                        $setClause .= "img_secondaire_8 = :img_secondaire_8,";
                        $params['img_secondaire_8'] = $newImgSecondaire8;
                    }

                    if (isset($_FILES['img_secondaire_9']) && !empty($_FILES['img_secondaire_9'])) {
                        if (file_exists('./public/images/' . $item['img_secondaire_9'])) {
                            unlink('./public/images/' . $item['img_secondaire_9']);
                        }
                        $newImgSecondaire9 = saveImageFile($_FILES['img_secondaire_9']);
                        $setClause .= "img_secondaire_9 = :img_secondaire_9,";
                        $params['img_secondaire_9'] = $newImgSecondaire9;
                    }

                    if (isset($_FILES['img_secondaire_10']) && !empty($_FILES['img_secondaire_10'])) {
                        if (file_exists('./public/images/' . $item['img_secondaire_10'])) {
                            unlink('./public/images/' . $item['img_secondaire_10']);
                        }
                        $newImgSecondaire10 = saveImageFile($_FILES['img_secondaire_10']);
                        $setClause .= "img_secondaire_10 = :img_secondaire_10,";
                        $params['img_secondaire_10'] = $newImgSecondaire10;
                    }



                    if (isset($name) && !empty($name)) {
                        $setClause .= "name = :name,";
                        $params['name'] = $name;
                    }

                    if (isset($price) && !empty($price)) {
                        $setClause .= "price = :price,";
                        $params['price'] = $price;
                    }
                    if (isset($description) && !empty($description)) {
                        $setClause .= "description = :description,";
                        $params['description'] = $description;
                    }
                    if (isset($artiste) && !empty($artiste)) {
                        $setClause .= "artiste = :artiste,";
                        $params['artiste'] = $artiste;
                    }
                    if (isset($epoque) && !empty($epoque)) {
                        $setClause .= "epoque = :epoque,";
                        $params['epoque'] = $epoque;
                    }
                    if (isset($style) && !empty($style)) {
                        $setClause .= "style = :style,";
                        $params['style'] = $style;
                    }
                    if (isset($etat) && !empty($etat)) {
                        $setClause .= "etat = :etat,";
                        $params['etat'] = $etat;
                    }
                    if (isset($matiere) && !empty($matiere)) {
                        $setClause .= "matiere = :matiere,";
                        $params['matiere'] = $matiere;
                    }
                    if (isset($longeur) && !empty($longeur)) {
                        $setClause .= "longeur = :longeur,";
                        $params['longeur'] = $longeur;
                    }
                    if (isset($largeur) && !empty($largeur)) {
                        $setClause .= "largeur = :largeur,";
                        $params['largeur'] = $largeur;
                    }
                    if (isset($diametre) && !empty($diametre)) {
                        $setClause .= "diametre = :diametre,";
                        $params['diametre'] = $diametre;
                    }
                    if (isset($hauteur) && !empty($hauteur)) {
                        $setClause .= "hauteur = :hauteur,";
                        $params['hauteur'] = $hauteur;
                    }
                    if (isset($profondeur) && !empty($profondeur)) {
                        $setClause .= "profondeur = :profondeur,";
                        $params['profondeur'] = $profondeur;
                    }

                    $setClause = rtrim($setClause, ', ');
                    try {
                        updateItem($setClause, $params);
                        http_response_code(200);
                        sendJSON(array("Success" => "Item updated"));
                    } catch (Exception $e) {
                        throw new Exception("Impossible de mettre à jour l'item");
                    }

                } catch (Exception $e) {
                    sendJSON(array("error" => $e->getMessage()));
                }
                break;

            case "GET":
                $item = getItemById($id);
                if (!$item) {
                    http_response_code(200);
                    sendJSON(array("error" => "Cette id ne correspond à aucun item"));
                  
                }
                unset($item['posterId']);
                http_response_code(200);
                sendJSON($item);
                break;

            case "PUT":

                http_response_code(500);
                sendJSON(array("error" => "On est en php donc le put c'est du post ..."));
                break;

            case "DELETE":
                // 
                try {
                    $item = getItemById($id);
                    if (!$item) {
                        throw new Exception("id inconnue");
                    }
                    $isProprietaire = isUuidTokenLikePosterIdItem($item);
                    if ($isProprietaire) {

                        // DELETE FILE FROM SERVER
                        if (file_exists('./public/images/' . $item['image'])) {
                            unlink('./public/images/' . $item['image']);
                        }
                        // DELETE ITEM FROM BDD
                        deleteNameItemFromBDD($item['uuid']);
                        http_response_code(200);
                        sendJSON(array("success" => "Item supprimé !"));
                    }
                } catch (Exception $e) {
                    http_response_code(500);
                    sendJSON(array("error" => $e->getMessage()));
                }
                break;
        }
    }


}

