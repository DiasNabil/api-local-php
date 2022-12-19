<?php
require '../../db/database.php';

/**
 * function prepare() + executeb() preferable a query () pour faire des requete a db 
 * prepare() : prepare une requete à l'execution par la methode execute() et return un object PDOstatement (requete preparer en attente d'execution)
 * execute() : execute une requete preparer (via prepare()) elle return true en cas de succès ou false si il ya une erreur 
 * fetchAll(): return une liste de toute les lignes de la requette executer sous forme d'array
 */
$getAllNotes = $dbh->prepare("SELECT * FROM notes");
$getAllNotes->execute();

format($getAllNotes->fetchAll());


function format($notes){

    global $state;

    $state['response']['message'] = 'liste de toute les notes recupérées';
    
    foreach($notes as $note){
        $array = array();
        $array['id'] = $note["id"];
        /** fonction explode() transforme une chaine de caractere en tableau */
        $array['tag'] = explode("," , $note["tag"]);
        $array["content"] = $note["content"];
        $array["date"] = $note["date"];

        array_push($state['response']['data'] , $array);
    };
};

?>