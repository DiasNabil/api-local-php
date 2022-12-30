<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>getById</title>
</head>
<body>
    

<h1>Test recup une note par son id</h1>

<form action="" method="post">
    <fieldset>
        <label for="id"> id </label>
        <br>
        <input type="text" name="id">
        <br>
        <hr>
        <input type="submit" value="envoyer">
    </fieldset>
</form>


</body>
</html>

<?php 

if (isset($_POST['id'])){

    header("Location: http://api.local/notes/".$_POST['id']);
}

?>