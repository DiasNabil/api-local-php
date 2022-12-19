<?php
header('Content-type: application/json');
require 'get.php';

$tags = array();
$notes = $state['response']['data'];
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

$state['response']['message'] = 'Liste des notes trier par tag';
$state['response']['data'] = $sortBytag;

echo json_encode($state['response']);



