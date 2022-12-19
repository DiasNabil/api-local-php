<?php
require '../db/database.php';

$id = $_POST['id'];

$displayDeletedNote = $dbh->prepare("SELECT * FROM notes WHERE id LIKE :id");
$displayDeletedNote->bindParam(':id', $id);
$displayDeletedNote->execute();
format($displayDeletedNote->fetch());
echo json_encode($state['response']);

$deleteNote = $dbh->prepare("DELETE FROM notes WHERE id LIKE :id" );
$deleteNote->bindParam(':id', $id);
$deleteNote->execute();



function format($note){

    global $state;

    $formatNotes = array();    
    $formatNotes['id'] = $note["id"];
    /** fonction explode() transforme une chaine de caractere en tableau */
    $formatNotes['tag'] = explode("," , $note["tag"]);
    $formatNotes["content"] = $note["content"];
    $formatNotes["date"] = $note["date"];

    $state['response']['message'] = "La note n°".$note['id']." a bien été supprimer";
    array_push($state['response']['data'] , $formatNotes);
};

?>