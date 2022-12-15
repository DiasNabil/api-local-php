<?php
require 'database.php';

/**info recup via formulaire */
$tag = $_POST['tag'];
$content = $_POST['content'];

echo "<h2>info recup ! </h2> <br> \n <h3> recap form :</h3> <br>\n tag : $tag <br>\n content : $content \n <br>";


?>