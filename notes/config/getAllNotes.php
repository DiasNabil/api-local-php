<?php
require 'database.php';

/**
 * fct query sert a envoyer des requete SQL a la db  directement 
 * elle return false en cas d'echec ou un PDO object
 */
$getAllNotes = "SELECT * FROM notes";
foreach($dbh->query($getAllNotes) as $row) {

    $row = format($row);
    print "\n $row \n";
    
}

function format($notes){
    $array = array('id' => $notes["id"],'tag' => $notes["tag"],"content" => $notes["content"],"date" => $notes["date"]);

    return json_encode($array);
};

?>