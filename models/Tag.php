<?php

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
            $tagSQL = "%$tag%";

            $getNotesByTag = $db->prepare("SELECT * FROM $table WHERE tag LIKE :tag" );
            /**methode PDOstatement bindParam() elle sert a lié une variable php a un parametre (:param) qu'on pourra utiliser dans des requetes SQL*/
            $getNotesByTag->bindParam(':tag', $tagSQL);
            $getNotesByTag->execute();
        
            return $this->format($getNotesByTag->fetchAll(), $tag);
        }

        public function sortByTag($notes) {
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
                $array['name'] = $tag;
                $array['notes']= array();  
        
                
                foreach($notes as $note){
                    if(array_search($tag, $note['tag']) !== false ){
        
                        array_push($array['notes'], $note); 
                    }
                }
        
                array_push($sortBytag, $array);
            }
    
            return $sortBytag;        
        }

        private function format($notes , $tagName){

            $data = array();

            $tag = array();
            $tag['name'] = $tagName;
            $tag['notes']= [];
            
            foreach($notes as $note){
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
                array_push($tag['notes'] , $array);
                
            };

            array_push($data, $tag);
            return $data;
        }
    }