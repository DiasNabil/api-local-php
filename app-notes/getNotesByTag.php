<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>getByTag</title>
</head>
<body>

    <h1>Test recup des notes par tag</h1>

    <form action='' method='post'>
        <fieldset>
            <label for='tag'> Tag: </label>
            <br>
            <input type='text' name='tag'>
            <br>
            <hr>
            <input type='submit' value='envoyer'>
        </fieldset>
    </form>
    
</body>
</html>

<?php 

if (isset($_POST['tag'])){

    header("Location: http://api.local/notes/bytags/".$_POST['tag']);
}