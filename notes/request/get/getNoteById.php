<?php

require '../../db/database.php';

$id = $_POST['id'];

$getNoteById = $dbh->prepare("SELECT * FROM notes WHERE id LIKE :id" );
/**methode PDOstatement bindParam() elle sert a lié une variable php a un parametre (:param) qu'on pourra utiliser dans des requetes SQL*/
$getNoteById->bindParam(':id', $id);
$getNoteById->execute();

/** resultat de la requete getNotesById au format json */
$note = format($getNoteById->fetch());
echo json_encode($note);


function format($note){

    $formatNotes = array();
    $formatNotes['message'] = "note n°".$note['id']." bien recupérée";

    $formatNotes['data'] = array();    
    $formatNotes['data']['id'] = $note["id"];
    /** fonction explode() transforme une chaine de caractere en tableau */
    $formatNotes['data']['tag'] = explode("," , $note["tag"]);
    $formatNotes['data']["content"] = $note["content"];
    $formatNotes['data']["date"] = $note["date"];


    return $formatNotes;
};

?>