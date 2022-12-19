<?php

require '../../db/database.php';

$tag = "%".$_POST['tag']."%";


$getNotesByTag = $dbh->prepare("SELECT * FROM notes WHERE tag LIKE :tag" );
/**methode PDOstatement bindParam() elle sert a lié une variable php a un parametre (:param) qu'on pourra utiliser dans des requetes SQL*/
$getNotesByTag->bindParam(':tag', $tag);
$getNotesByTag->execute();

/** resultat de la requete getNotesById au format json */
format($getNotesByTag->fetchAll());
echo json_encode($state['response']);


function format($notes){

    global $state;

    $state['response']['message'] = "les notes contenant le tag ".$_POST['tag']." ont bien été recupérées";
        
    foreach ($notes as $note) {
        $array = array();
        $array['id'] = $note["id"];
        /** fonction explode() transforme une chaine de caractere en tableau */
        $array['tag'] = explode("," , $note["tag"]);
        $array["content"] = $note["content"];
        $array["date"] = $note["date"];

        array_push($state['response']['data'], $array);
    }

};

?>