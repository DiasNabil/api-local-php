<?php

require '../../db/database.php';

$id = $_POST['id'];

$getNoteById = $dbh->prepare("SELECT * FROM notes WHERE id LIKE :id" );
/**methode PDOstatement bindParam() elle sert a lié une variable php a un parametre (:param) qu'on pourra utiliser dans des requetes SQL*/
$getNoteById->bindParam(':id', $id);
$getNoteById->execute();

/** resultat de la requete getNotesById au format json */
format($getNoteById->fetch());
echo json_encode($state['response']);


function format($note){

    global $state;

    $formatNotes = array();    
    $formatNotes['id'] = $note["id"];
    /** fonction explode() transforme une chaine de caractere en tableau */
    $formatNotes['tag'] = explode("," , $note["tag"]);
    $formatNotes["content"] = $note["content"];
    $formatNotes["date"] = $note["date"];

    $state['response']['message'] = "note n°".$note['id']." bien recupérée";
    array_push($state['response']['data'] , $formatNotes);
};

?>