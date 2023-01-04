<?php 
/**
 * Role de controller 
 * il gere chaque route de l'api 
 * et l'action a mener dans ces routes 
 * 
 */
require 'config/database.php';
require 'models/Note.php';
require 'models/Tag.php';

$db = new Database;
$note = new Note($db);
$tag = new Tag($db);


// Gestion des routes de l'api
try {
    /** Variable 'request' expliquée dans le document .htaccess  */
    if (!empty($_GET['request'])) {
        $url = explode('/', filter_var($_GET['request'], FILTER_SANITIZE_URL));

        /**  */
        switch ($url[0]) {
            case 'notes':
                switch ($url[1]) {
                    case '':
                        toJSON($note->getAllNotes());
                        break;
                    
                    case is_numeric($url[1]): 
                        $id = intval($url[1]);
                        toJSON($note->getNoteById($id));
                        break;

                    case 'add':
                        toJSON($note->createNote($_POST['tag'],$_POST['content']));
                        break;

                    case 'delete':
                        toJSON($note->deleteNote($_POST['id']));
                        break;

                    case 'update':
                        toJSON($note->updateNote($_POST['id']));
                        break;

                    case 'bytags': 
                        switch ($url[2]) {
                            case '':
                                $notes = $note->getAllNotes();
                                toJSON($tag->sortByTag($notes));
                                break;
                            
                            default:
                                toJSON($tag->getNotesByTag($url[2]));
                                break;
                        }
                        break;


                    default:
                        throw new Exception ("La demande ne peut aboutir, veuillez verifier l'url /2");
                        break;
                }
                
                break;
            
            default:
                throw new Exception ("La demande ne peut aboutir, veuillez verifier l'url  /1");
                break;
        }

    }else{
        /**
         * generer une page d'accueil
         */
         echo '
         <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>app-notes | API</title>
            </head>
            <body>
            <h1>app-notes | API</h1>

            <h2> Prise de note</h2>
            <ul>
                <li><a href="/app-notes/test_form.html">Ajouter une nouvelle note</a></li>
                <li><a href="/app-notes/updateNote.html">Modifier une nouvelle note</a></li>
                <li><a href="/app-notes/deleteNote.html">Supprimer une note</a></li>
                <li><a href="/notes/">Liste des notes</a></li>
                <li><a href="/notes/bytags">Liste des notes trier par tag</a></li>
                <li><a href="/app-notes/getNoteById.php">Trouver une note par son id</a></li>
                <li><a href="/app-notes/getNotesByTag.php">Trouver des notes par tag</a></li>
                
            </ul>
            </body>
            </html>
         ';
        
    }
}catch (Exception $e){
    echo "erreur ! : " . $e->getMessage();
}


function toJSON($data){
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
?>


