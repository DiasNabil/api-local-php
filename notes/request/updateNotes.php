<?php
require '../db/database.php';

/**info recup via formulaire */
$id = $_POST['id'];
$tag = formatTag($_POST['tag']);
$content = $_POST['content'];

$updateNote = $dbh->prepare("UPDATE notes SET tag = :tag , content = :content WHERE id LIKE :id;" );
$updateNote->bindParam(':id', $id);
$updateNote->bindParam(':tag', $tag);
$updateNote->bindParam(':content', $content);
$updateNote->execute();

$displayUpdatedNote = $dbh->prepare("SELECT * FROM notes WHERE id LIKE :id");
$displayUpdatedNote->bindParam(':id', $id);
$displayUpdatedNote->execute();
format($displayUpdatedNote->fetch());
echo json_encode($state['response']);



function formatTag($tags){

    $tags = trim($tags);
    $tags = explode(" ", $tags);
    $tags = array_unique($tags);
    
    foreach($tags as $tag){
        
        if ($tag === '') {
            $index = array_search($tag, $tags);
            array_splice($tags,$index, 1);
        }
    };

    /**function implode() change un tableau en chaine de charactere */
    $tags = implode(",", $tags);
    
    return $tags;
};

function format($note){

    global $state;

    $formatNotes = array();    
    $formatNotes['id'] = $note["id"];
    /** fonction explode() transforme une chaine de caractere en tableau */
    $formatNotes['tag'] = explode("," , $note["tag"]);
    $formatNotes["content"] = $note["content"];
    $formatNotes["date"] = $note["date"];

    $state['response']['message'] = "La note n°".$note['id']." a bien été modifier";
    array_push($state['response']['data'] , $formatNotes);
};

?>