<?php
 class Database {

    /**
     * info servant a la connexion a la db
     * preferable de store ces info dans un .ini files et restreindre son accées 
     */

    private $user = 'root';
    private $password ='';
    private $host = '127.0.0.1';
    private $dbName = 'notes';



    public function getConnexion(){

        $connect = null;

        try {
            /**
             * on se connecte a notre db en créant une nvlle instance de PDO avec les info de notre db
             */
        
            $connect = new PDO(
                "mysql:host=$this->host;dbname=$this->dbName", 
                $this->user,
                $this->password
            );

            }  catch (PDOException $e) {
                print "Erreur !: " . $e->getMessage() . "<br/>";
                die();
                };
        
        return $connect;

    }
 
 }

 