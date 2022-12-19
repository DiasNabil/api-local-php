<?php

/**
 * info servant a la connexion a la db
 * preferable de store ces info dans un .ini files et restreindre son accées 
 */

 $user = 'root';
 $password ='';

 $state = array();
 $state['db'] = '';
 $state['response'] = array();
 $state['response']['message']='';
 $state['response']['data']= array();

 
 
 /** 
  * try catch pour la gestion d'erreur
  */
 try {
 /**
  * on se connecte a notre db en créant une nvlle instance de PDO avec les info de notre db
  */

 $dbh = new PDO('mysql:host=127.0.0.1;dbname=notes', $user, $password);
 $state['db'] = 'connected';
 
 
 }  catch (PDOException $e) {
     print "Erreur !: " . $e->getMessage() . "<br/>";
     die();
 }

?>