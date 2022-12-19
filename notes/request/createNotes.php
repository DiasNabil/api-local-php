<?php
require '../db/database.php';

/**info recup via formulaire */
$tag = formatTag($_POST['tag']);
$content = $_POST['content'];

$createNote = $dbh->prepare("INSERT INTO notes (tag , content) VALUES (:tag , :content)" );
$createNote->bindParam(':tag', $tag);
$createNote->bindParam(':content', $content);
$createNote->execute();

$displayCreatedNote = $dbh->prepare("SELECT * FROM notes ORDER BY id DESC LIMIT 1;");
$displayCreatedNote->execute();
format($displayCreatedNote->fetch());
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

    $state['response']['message'] = "La note n°".$note['id']." a bien été enregistrer";
    array_push($state['response']['data'] , $formatNotes);
};

?>