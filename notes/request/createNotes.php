<?php
require '../db/database.php';

/**info recup via formulaire */
$tag = formatTag($_POST['tag']);
$content = $_POST['content'];

echo "<br> \n <h3> recap form :</h3> <br>\n tag : $tag <br>\n content : $content \n <br>";

function formatTag($tags){

    $tags = trim($tags);
    $tags = explode(" ", $tags);
    
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

?>