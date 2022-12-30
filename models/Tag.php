<?php
    /**
     * fonction liste de tout les tag 
     * liste des notes classé par tag 
     * liste des notes selon le tag rechercher 
     */


    class Tag{

        private $table = 'notes';
        private $connect = null;

        public function __construct($db)
        {   
            if ($this->connect === null) {
                $this->connect = $db->getConnexion();
            }
        } 

        public function getNotesByTag($tag){

            $db = $this->connect;
            $table = $this->table;

            $getNotesByTag = $db->prepare("SELECT * FROM $table WHERE tag LIKE :tag" );
            /**methode PDOstatement bindParam() elle sert a lié une variable php a un parametre (:param) qu'on pourra utiliser dans des requetes SQL*/
            $getNotesByTag->bindParam(':tag', $tag);
            $getNotesByTag->execute();
        
            //$state['response']['message'] = "les notes contenant le tag '".$tag."'  ont bien été recupérées";
            return $this->format($getNotesByTag->fetchAll());
        
            //return $state['response'];
        }

        public function sortByTag($notes) {
            //global $state;
            //$all = getAllNotes();
        
            $tags = array();
            $sortBytag = array();
        
            foreach ($notes as $note) {
                foreach ($note['tag'] as $tag) {
                    
                    $tag = trim($tag);
                    array_push($tags,$tag);
                }
            }
        
            $tags = array_unique($tags);
            natsort($tags);
        
            foreach ($tags as $tag) {
                $array = array();
                $array['tag'] = $tag;
                $array['notes']= array();  
        
                
                foreach($notes as $note){
                    if(array_search($tag, $note['tag']) !== false ){
        
                        array_push($array['notes'], $note); 
                    }
                }
        
                array_push($sortBytag, $array);
            }
        
            //$state['response']['message'] = 'Liste des notes trier par tag';
            //$state['response']['data'] = $sortBytag;
        
            //return $state['response'];

            return $sortBytag;
            
        }

        function format($fetch){

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

    }