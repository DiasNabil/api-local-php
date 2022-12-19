<?php
/**
 * permet au navigateur d'interpreter le fichier php comme un fichier au format JSON
*/
header('Content-type: application/json');


require 'get.php';

/** resultat de la requete getAllNotes du fichier get.php au format json */
echo json_encode($state['response']);


?>