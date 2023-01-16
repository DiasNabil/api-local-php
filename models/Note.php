<?php

/**
 * fonction recup allnotes 
 * fonction recup une note
 * fonction ajout d'une note
 * fonction modif une note
 * fonction suppr une note
 * 
 * SELECT * FROM notes INNER JOIN exercises WHERE notes.id = exercises.note_id
 */


class Note
{
    // les methodes et propriétés necessaire a la gestion des donnnées de la tables notes

    private $tableNote = 'notes';
    private $tableEx = 'exercises';
    private $connect = null;

    public function __construct($db)
    {
        if ($this->connect === null) {
            $this->connect = $db->getConnexion();
        }
    }

    public function getAllNotes()
    {

        $db = $this->connect;
        $tableNote = $this->tableNote;
        /**
         * function prepare() + executeb() preferable a query () pour faire des requete a db 
         * prepare() : prepare une requete à l'execution par la methode execute() et return un object PDOstatement (requete preparer en attente d'execution)
         * execute() : execute une requete preparer (via prepare()) elle return true en cas de succès ou false si il ya une erreur 
         * fetchAll(): return une liste de toute les lignes de la requette executer sous forme d'array
         */
        $getAllNotes = $db->prepare(
            "SELECT *
            FROM $tableNote"
        );
        $getAllNotes->execute();
        $data = $this->format($getAllNotes->fetchAll());

        if ($data != []) {
            return $data;
        } else return 'Notes introuvable';
    }

    public function getNoteById($id)
    {
        $db = $this->connect;
        $tableNote = $this->tableNote;

        $getNoteById = $db->prepare("
        SELECT notes.tag, notes.id AS note_id, notes.date, notes.content, exercises.id AS id, exercises.exercise, exercises.reps, exercises.sets, exercises.obs 
        FROM $tableNote
        INNER JOIN exercises 
        WHERE notes.id = exercises.note_id AND notes.id LIKE :id");
        /**methode PDOstatement bindParam() elle sert a lié une variable php a un parametre (:param) qu'on pourra utiliser dans des requetes SQL*/
        $getNoteById->bindParam(':id', $id);
        $getNoteById->execute();

        /** resultat de la requete getNoteById au format json */
        $fetch = $this->format($getNoteById->fetchAll());

        if (count($fetch) > 0) {
            $data = array();
            $data['id'] = $fetch[0]['note_id'];
            $data['tag'] = $fetch[0]['tag'];
            $data['content'] = $fetch[0]['content'];
            $data['date'] = $fetch[0]['date'];
            $data['exercises'] = [];

            foreach ($fetch as $exo) {
                $exercice = array();
                foreach ($exo as $key => $value) {
                    if ($key !== 'tag' && $key !== 'note_id' && $key !== 'content' && $key !== 'date') {
                        $exercice[$key] = $value;
                    }
                };
                array_push($data['exercises'], $exercice);
            }


            return $data;
        } else return 'Note introuvable';
    }

    public function createNote($data)
    {
        $tag = $this->formatTag($data['tag']);
        $content = $data['content'];

        $db = $this->connect;
        $tableNote = $this->tableNote;
        $tableEx = $this->tableEx;

        if ($tag != '' && $content != '') {
            $createNote = $db->prepare("INSERT INTO $tableNote (tag , content) VALUES (:tag , :content)");
            $createNote->bindParam(':tag', $tag);
            $createNote->bindParam(':content', $content);
            $createNote->execute();

            $createdNote = $db->prepare("SELECT * FROM $tableNote ORDER BY id DESC LIMIT 1;");
            $createdNote->execute();
            $createdNote = $this->format($createdNote->fetchAll());
            $noteId = $createdNote[0]['id'];

            // ajout des exo au tableau exercices 
            if (count($data['exercises']) > 0) {
                foreach ($data['exercises'] as $exo) {
                    $createEx = $db->prepare("INSERT INTO $tableEx (`id`, `note_id`, `exercise`, `reps`, `sets`, `obs`) VALUES (NULL, :note, :exercise , :reps , :sets, :obs)");
                    foreach ($exo as $key => &$value) {
                        // il faut passer $value par reference '&$value'(=$value fait reference a la valeur que contient la variable $value elle ne represente plus la valeur directement) 
                        //pour que bindParam() fonctionne avec $key en :parametre 
                        $createEx->bindParam("$key", $value);
                    }
                    $createEx->bindParam(':note', $noteId);
                    $createEx->execute();
                }
            }

            return $this->getNoteById($noteId);
        } else {
            return 'champs tag ou contenu manquant';
        }
    }

    public function deleteNote($note)
    {

        $db = $this->connect;
        $table = $this->tableNote;
        $id = $note['id'];

        $displayDeletedNote = $this->getNoteById($id);

        if (gettype($displayDeletedNote) !== "string") {
            $deleteNote = $db->prepare("DELETE FROM $table WHERE id LIKE :id");
            $deleteNote->bindParam(':id', $id);
            $deleteNote->execute();
            return $displayDeletedNote;
        } else return 'Note introuvable';
    }

    public function updateNote($data)
    {
        $db = $this->connect;
        $tableNote = $this->tableNote;
        $tableEx = $this->tableEx;

        $tag = $this->formatTag($data['tag']);
        $content = $data['content'];
        $noteId = $data['id'];

        // MAJ de la note dans la table note
        if ($tag != '' && $content != '') {
            $updateNote = $db->prepare("UPDATE $tableNote SET tag = :tag , content = :content WHERE id LIKE :id");
            $updateNote->bindParam(':id', $noteId);
            $updateNote->bindParam(':tag', $tag);
            $updateNote->bindParam(':content', $content);
            $updateNote->execute();

            //MAJ des exo de la notes dans la table exercices
            if (count($data['exercises']) > 0) {
                foreach ($data['exercises'] as $exo) {
                    $updateEx = $db->prepare("UPDATE $tableEx SET  exercise = :exercise, reps = :reps, `sets` = :sets, obs = :obs WHERE id LIKE :id");
                    foreach ($exo as $key => &$value) {
                        // il faut passer $value par reference '&$value'(=$value fait reference a la valeur que contient la variable $value elle ne represente plus la valeur directement) 
                        //pour que bindParam() fonctionne avec $key en :parametre 
                        $updateEx->bindParam("$key", $value);
                    }
                    $updateEx->execute();
                }
            }
            return $this->getNoteById($noteId);
        } else {
            return 'champs tag ou contenu manquant';
        }
    }

    /**
     * fonction de format les notes recup dans la db
     */

    private function format($fetch)
    {

        $data = array();

        foreach ($fetch as $note) {
            $array = array();

            foreach ($note as $key => $value) {
                if (!is_numeric($key)) {
                    if ($key === 'tag') {
                        $array['tag'] = explode(",", $note["tag"]);
                    } else {
                        $array[$key] = $value;
                    }
                }
            }
            array_push($data, $array);
        };
        return $data;
    }

    private function formatTag($tags)
    {

        $tags = trim($tags);
        $tags = explode(" ", $tags);
        $tags = array_unique($tags);

        foreach ($tags as $tag) {

            if ($tag === '') {
                $index = array_search($tag, $tags);
                array_splice($tags, $index, 1);
            }
        };

        /**function implode() change un tableau en chaine de charactere */
        $tags = implode(",", $tags);

        return $tags;
    }
}
