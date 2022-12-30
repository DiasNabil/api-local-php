<?php

/**
 * fonction recup allnotes 
 * fonction recup une note
 * fonction ajout d'une note
 * fonction modif une note
 * fonction suppr une note
 */


class Note {
    // les methodes et propriétés necessaire a la gestion des donnnées de la tables notes

    private $table = 'notes';
    private $connect = null; 


    // les proprietes de l'objet note

    public $id;
    public $tag;
    public $content;
    public $date;

    public function __construct($db)
    {   
        if ($this->connect === null) {
            $this->connect = $db->getConnexion();
        }
    }

    public function getAllNotes(){
        

        //global $state, $dbh;
        
        $db = $this->connect;
        $table = $this->table;
        /**
     * function prepare() + executeb() preferable a query () pour faire des requete a db 
     * prepare() : prepare une requete à l'execution par la methode execute() et return un object PDOstatement (requete preparer en attente d'execution)
     * execute() : execute une requete preparer (via prepare()) elle return true en cas de succès ou false si il ya une erreur 
     * fetchAll(): return une liste de toute les lignes de la requette executer sous forme d'array
     */
        $getAllNotes = $db->prepare("SELECT * FROM $table");
        $getAllNotes->execute();
    
        //$state['response']['message'] = 'liste de toute les notes recupérées';
        return $this->format($getAllNotes->fetchAll());

    }

    public function getNoteById($id){
        //global $state, $dbh;
        $db = $this->connect;
        $table = $this->table;
    
        $getNoteById = $db->prepare("SELECT * FROM $table WHERE id LIKE :id" );
        /**methode PDOstatement bindParam() elle sert a lié une variable php a un parametre (:param) qu'on pourra utiliser dans des requetes SQL*/
        $getNoteById->bindParam(':id', $id);
        $getNoteById->execute();
        
        /** resultat de la requete getNoteById au format json */
        //$state['response']['message'] = "note n°".$id." bien recupérée";
        return $this->format($getNoteById->fetchAll());
    
        //return $state['response'];
    }

    public function createNote($tag, $content){
    $tag = $this->formatTag($tag);

    $db = $this->connect;
    $table = $this->table;
    

    $createNote = $db->prepare("INSERT INTO $table (tag , content) VALUES (:tag , :content)" );
    $createNote->bindParam(':tag', $tag);
    $createNote->bindParam(':content', $content);
    $createNote->execute();

    $displayCreatedNote = $db->prepare("SELECT * FROM $table ORDER BY id DESC LIMIT 1;");
    $displayCreatedNote->execute();
    return $this->format($displayCreatedNote->fetchAll());
    //echo json_encode($state['response']);

    }

    public function deleteNote($id){

        $db = $this->connect;
        $table = $this->table;

        $displayDeletedNote = $db->prepare("SELECT * FROM $table WHERE id LIKE :id");
        $displayDeletedNote->bindParam(':id', $id);
        $displayDeletedNote->execute();
        $data = $this->format($displayDeletedNote->fetchAll());
        //echo json_encode($state['response']);

        $deleteNote = $db->prepare("DELETE FROM $table WHERE id LIKE :id" );
        $deleteNote->bindParam(':id', $id);
        $deleteNote->execute();

        return $data;
    }

    public function updateNote($id){
        $db = $this->connect;
        $table = $this->table;

        $tag = $this->formatTag($_POST['tag']);
        $content = $_POST['content'];

        $updateNote = $db->prepare("UPDATE $table SET tag = :tag , content = :content WHERE id LIKE :id;" );
        $updateNote->bindParam(':id', $id);
        $updateNote->bindParam(':tag', $tag);
        $updateNote->bindParam(':content', $content);
        $updateNote->execute();

        $displayUpdatedNote = $db->prepare("SELECT * FROM $table WHERE id LIKE :id");
        $displayUpdatedNote->bindParam(':id', $id);
        $displayUpdatedNote->execute();
        $data = $this->format($displayUpdatedNote->fetchAll());
        //echo json_encode($state['response']);

        return $data;
        
    }


    /**
     * fonction de format les notes recup dans la db
     */

    public function format($fetch){

        //global $state;
        $data = array();
        
        foreach($fetch as $note){
            $array = array();

            foreach ($note as $key => $value) {
                if (!is_numeric($key)) {
                    if($key === 'tag'){
                        $array['tag'] = explode("," , $note["tag"]);
                    }else {
                        $array[$key] = $note[$key];    
                    }
                            
                }
            }    
            //array_push($state['response']['data'] , $array);
            array_push($data , $array);
        };
        return $data;
    }

    public function formatTag($tags){

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
    }


}