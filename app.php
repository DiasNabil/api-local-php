<?php
/**
 * info servant a la connexion a la db
 */

$user = 'root';
$password ='';

/** 
 * try catch pour la gestion d'erreur
 */
try {
/**
 * on se connecte a notre db en créant une nvlle instance de PDO avec les info de notre db
 */
$dbh = new PDO('mysql:host=127.0.0.1;dbname=notes', $user, $password);
print "\n connecté à la base de donnée !\n" ;

/**
 * fct query sert a envoyer des requete SQL a la db  directement 
 * elle return false en cas d'echec ou un PDO object
 */
$getAllNotes = "SELECT * FROM notes";
foreach($dbh->query($getAllNotes) as $row) {

    $row = format($row);
    print "\n $row \n";
    
}


}  catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

function format($notes){
    $array = array('id' => $notes["id"],'tag' => $notes["tag"],"content" => $notes["content"],"date" => $notes["date"]);

    return json_encode($array);
};


?>